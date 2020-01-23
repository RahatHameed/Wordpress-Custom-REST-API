<?php
/**.
 * User: Rahat Hameed
 * Date: 23-Jan-20
 * Time: 5:29 PM
 */

add_action( 'rest_api_init', function () {

    $namespace = 'wp_custom_apis/v1';

    register_rest_route( $namespace, 'get_helloworld', array(
        'methods' => 'GET',
        'callback' => 'helloworld',
    ));


    function helloworld(){

        $data = array(
                'message'=> 'Hello World',
                'API endoint' => 'helloworld'
            );
        return new WP_REST_Response( $data, 200 );
    }


    register_rest_route( $namespace, 'get_posts', array(
        'methods' => 'GET',
        'callback' => 'get_posts_callback',
        'permission_callback' => function () {
            return true;
        }
    ) );


    function get_posts_callback($request){

        $parameters = $request->get_params();
        $post_author = $parameters['post_author'];
        $numberOfPosts = $parameters['numberposts'];


        If($post_author==''){
            $post_author='all';
        }

        if($numberOfPosts==''){
            // default no of posts
            $numberOfPosts=2;
        }

        $postArguments = array(
                    'type' => 'post',
                    'author' => $post_author,
                    'numberposts',$numberOfPosts
                    );

        $posts_list = get_posts(  );
        $post_data = array($postArguments);

        foreach( $posts_list as $posts) {
            $post_id = $posts->ID;
            $post_author = $posts->post_author;
            $post_title = $posts->post_title;
            $post_content = $posts->post_content;

            $post_data[ $post_id ][ 'author' ] = $post_author;
            $post_data[ $post_id ][ 'title' ] = $post_title;
            $post_data[ $post_id ][ 'content' ] = $post_content;
        }

        wp_reset_postdata();

        if ( $post_data) {
                return new WP_REST_Response( $post_data, 200 );
        }else{
                $data=array('status'=>'failed', 'name'=>'test');
                return new WP_Error( 'error','some thing went wrong',$data);
        }

    }


} );

?>