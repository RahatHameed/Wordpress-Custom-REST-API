# Wordpress-Custom-REST-API
Wordpress REST api comes with some default routes, some times you need to extend Wordpres REST API and create your own custom routes and endpoints.

Place this file in root directory of wordpress and add following code to include it inside your functions.php file.
require_once('wp_rest_api.php');

<b>Below is simple code to create Helloworld end point for REST API</b>

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
    } );
    
# Parameters
We are using register_rest_route with below parameters:
<b>First Parameter:</b> this is our custom namespace  custom_apis/v1
<b>Second Parameter:</b> this is our end point.


# Example

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
    
# How to Call It:

http://domain_name/wp-json/[NameSpace]/[EndPoint]
In above example our REST API url will be as follow
http://domain_name/wp-json/wp_custom_apis/v1/get_helloword 

