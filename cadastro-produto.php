<?php
require_once dirname(__FILE__) . '/lib/class-tgm-plugin-activation.php';
//require_once dirname(__FILE__) .'/inc/cpts.php';
/*
Plugin Name: Cadastro de Produtos
Description: Plugin de cadastro e consulta de produtos. Este plugin faz parte do Portfolio do autor e pretende demonstrar um sistema CRUD básico de um Cadastro de Produtos no Back-End e uma Página de Busca dos mesmos a partir do seu número de série no Front-End.
Version: 1.0
Author: Jose Paulo Carvalho
Author URI: https://fat32.com.br
Text Domain: cadastro-produto
License: GPL2
*/
//require_once(plugin_dir_path(__FILE__).'/update.php');
//require(plugin_dir_path(__FILE__).'/inc/cadastro-produto-update.php');

class Product
{


  const FIELD_PREFIX = 'cp_';
  const TEXT_DOMAIN = 'cadastro-produto';
  const TAXONOMY = 'cadastro-produto-category';

  const TABLE_NAME = 'cadastro_produto';
  const REVIEW_RATING = 'cadastro_produto';

  private static $instance;




















  public static function getInstance() 
  {
    if ( self::$instance == NULL ) 
    {
      self::$instance = new self();
      
    }//end if

    return self::$instance;

  }//end method





























  /*
  function load_admin_scripts()
  {

    wp_enqueue_style( 
        
      'template-admin-css', //identificador
      'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css', //caminho
      array(), //dependências
      '4.4.1', //versão (nao obrigatorio)
      'all' //tipo de midia (print, screen, all)

    );


  }//end function
  */































  private function __construct() 
  {


      add_filter( 'post_type_link', 'Product::gp_remove_cpt_slug', 10, 2 );

      add_action( 'pre_get_posts', 'Product::gp_add_cpt_post_names_to_main_query' );

      add_action( 'admin_menu', array( $this,'set_custom_fields') );

      //add_shortcode('twitter',array($this,'twitter'));

      /*
      add_action(

          'wp_enqueue_scripts', //Nome do gancho de ação
          'load_admin_scripts' //função criada
      
      );//end function*/

      //add_action('init','Product::register_post_type',0);
  
      //add_action('init', 'Product::register_taxonomies',0 );
      
      //add_filter( 'rwmb_meta_boxes', array($this,'metabox_custom_fields'));
      
      
      add_action( 'init', 'Product::product_register', 0 );
      
      add_filter( 'rwmb_meta_boxes', 'Product::get_meta_box' );
      
      add_action( 'init', 'Product::product_category_register', 0 );
      
      add_action( 'admin_enqueue_scripts', array($this,'load_admin_scripts') );

      add_action('wp_enqueue_scripts',array($this,'load_scripts'));
      
      add_action('tgmpa_register', array($this,'check_required_plugins'));

      add_action('template_include',array($this,'add_cpt_template'));

      //add_action('init', 'Product::rewrite_rules_for_removing_post_type_slug', 1, 1);
      add_action('wp_footer', 'Product::meu_plugin_altera_rodape',0);
      
      
      //add_action('plugin_credentials', 'Product::meu_plugin_altera_rodape');



  }//end construct


























  public static function meu_plugin_altera_rodape()
  {

      ?>

        <div class="footer1">
          <h3>

            <?php echo __("Acesse o Admin do WordPress para adicionar você mesmo outros números de série no Plugin:",Product::TEXT_DOMAIN); ?>

          </h3>
          <p><a target="_blank" href="https://plugin.fat32.com.br/wp-login.php">https://plugin.fat32.com.br/wp-login.php</a></p>

          <p><?php echo __("Login: plugin",Product::TEXT_DOMAIN) . ' | ' . __("Senha: plugin",Product::TEXT_DOMAIN); ?></p>

        </div

      <?php

  }//end meu_plugin_altera_rodape























  /*
  public static function rewrite_rules_for_removing_post_type_slug()
  {
      add_rewrite_rule(
          '(.?.+?)?(:/([0-9]+))?/?$',
          'index.php?cadastro-produto=$matches[1]/$matches[2]&post_type=cadastro-produto',
          'bottom'
      );

  }//end method
  */




















  public static function gp_remove_cpt_slug( $post_link, $post ) 
  {
    if ( Product::TEXT_DOMAIN === $post->post_type && 'publish' === $post->post_status )
    {
        $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );

    }//end if
    return $post_link;
  }//emd method






































  public static function gp_add_cpt_post_names_to_main_query( $query ) 
  {

    

    // Bail if this is not the main query.
    if ( !$query->is_main_query() ) 
    {
      return;

    }//end if

    //echo '<pre>';
    //var_dump(!$query->is_main_query());
    
    //var_dump(!isset( $query->query['page'] ) || 2 !== count( $query->query ));
    //var_dump(empty( $query->query['name'] ));
    //var_dump($query->query['name']);
    //var_dump( empty( $query->query['name'] ) && empty( $query->query['pagename'] ));
    //var_dump($query);

    // Bail if this query doesn't match our very specific rewrite rule.
    if ( !isset( $query->query['page'] ) || 2 !== count( $query->query ) ) 
    {
      return;

    }//end if



    // Bail if we're not querying based on the post name.
    if ( empty( $query->query['name'] ) && empty( $query->query['pagename'] ) ) 
    {
      return;

    }//end if




    // Add CPT to the list of post types WP will include when it queries based on the post name.
    $query->set( 'post_type', array( 'post', 'page', Product::TEXT_DOMAIN ) );



  }//end method



























  public function add_cpt_template( $template )
  {

      

      if( is_singular( Product::TEXT_DOMAIN ) )
      {
  
          if( file_exists( get_stylesheet_directory() . 'single-cadastro-produto.php' ) )
          {
  
                return get_stylesheet_directory() . 'single-cadastro-produto.php';
  
          }//end if
  
          return plugin_dir_path(__FILE__) . 'templates/single-cadastro-produto.php';

      }//end if
  
      return $template;
  
  }//END add_cpt_template

    




































  public function load_admin_scripts()
  {

    //wp_register_style( 'am_admin_bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css' );

    //wp_enqueue_style( 'am_admin_bootstrap');

    /*
    
    wp_enqueue_style( 
        
      'template-css', //identificador
      'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css', //caminho
      array(), //dependências
      '4.4.1', //versão (nao obrigatorio)
      'all' //tipo de midia (print, screen, all)

    );
    */

    

    wp_enqueue_style( 
        
      'template', //identificador
      plugin_dir_url(__FILE__).'css/template.css', //caminho
      array(), //dependências
      '1.0', //versão (nao obrigatorio)
      'all' //tipo de midia (print, screen, all)

    );


    
    /*
    
    wp_enqueue_script(

      'bootstrap-css', //identificador
      'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js', //caminho
      array( 'jquery' ), //dependências
      '4.4.1', //versão (nao obrigatorio)
      true //onde inserir o script (footer - true ou header - false (padrão))

    );




    wp_enqueue_script(

      'main', //identificador
      plugin_dir_url(__FILE__).'js/main.js', //caminho
      array( 'jquery' ), //dependências
      null, //versão (nao obrigatorio)
      true //onde inserir o script (footer - true ou header - false (padrão))

    );*/






  }//end method










































  public function load_scripts()
  {

    wp_enqueue_style( 
        
      'template-css', //identificador
      'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css', //caminho
      array(), //dependências
      '4.4.1', //versão (nao obrigatorio)
      'all' //tipo de midia (print, screen, all)

    );



    wp_enqueue_style( 
        
      'template', //identificador
      plugin_dir_url(__FILE__) . '/css/template.css', //caminho
      array(), //dependências
      '1.0', //versão (nao obrigatorio)
      'all' //tipo de midia (print, screen, all)

    );


    


    wp_enqueue_script(

      'bootstrap-css', //identificador
      'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js', //caminho
      array( 'jquery' ), //dependências
      '4.4.1', //versão (nao obrigatorio)
      true //onde inserir o script (footer - true ou header - false (padrão))

    );
    
    
    
    wp_enqueue_script(

      'main', //identificador
      plugin_dir_url(__FILE__) . '/js/main.js', //caminho
      array( 'jquery' ), //dependências
      null, //versão (nao obrigatorio)
      true //onde inserir o script (footer - true ou header - false (padrão))

    );

  }//end method



  






























  public function set_custom_fields()
  {

      add_menu_page(

        'Cadastro de Produtos',
        'Cadastro de Produtos',
        'manage_options',
        'product',
        'Product::list',
        'dashicons-index-card',
        '20'

      );

      /*
      add_options_page(

        'Adicionar Novo',
        'Adicionar Novo',
        'manage_options', // Minimum capability to view this page
        'Product', // Unique identifier
        'Product::createProductWarranty' //Callback function to get the contents

      );*/


      add_submenu_page(

        'product',
        'Adicionar Novo',
        'Adicionar Novo',
        'manage_options',
        'create_product',
        'Product::create',
        1

      );


      add_submenu_page(

        'options.php',
        'Editar',
        'Editar',
        'manage_options',
        'edit_product',
        'Product::edit',
        null

      );


      add_submenu_page(

        'options.php',
        'Update',
        'Update',
        'manage_options',
        'update_product',
        'Product::update',
        null

      );
      
      
      add_submenu_page(

        'options.php',
        'Delete',
        'Delete',
        'manage_options',
        'delete_product',
        'Product::delete',
        null

      );


      add_submenu_page(

        'options.php',
        'Search',
        'Search',
        'manage_options',
        'search_product',
        'Product::search',
        null

      );




  }//end method




























  public static function create_product_table()
  {

    try 
    {
      //code...
      global $wpdb;

      $table_name = $wpdb->prefix . Product::TABLE_NAME; 

      

      $charset_collate = $wpdb->get_charset_collate();



      $sql = "CREATE TABLE $table_name (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        product varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
        serial_number bigint(20) unsigned NOT NULL,
        warranty_term int(11) unsigned NOT NULL,
        product_modified datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        product_date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
      ) $charset_collate;";



      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

      dbDelta( $sql );

      return true;

    }//end try
    catch (\Throwable $th) 
    {
      //throw $th;
      return false;

    }//end catch

  }//end method




































  public static function list()
  {

    global $wpdb;

    $table_name = $wpdb->base_prefix . Product::TABLE_NAME;

    //echo '<pre>';
    //var_dump($table_name);
    //var_dump(!$wpdb->get_var( $query ) == $table_name);

    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

    if ( !$wpdb->get_var( $query ) == $table_name ) 
    {

      $handler = Product::create_product_table();

      if( $handler == false )
      {

        $html = '<h3>';
        $html .= __('Houve uma falha momentânea, por favor, carreue a página novamente',Product::TEXT_DOMAIN);
        $html .= '</h3>';

        echo $html;
        exit;

      }//end if
    
      //echo 'table_not_Exists';

    }//end if

    $results = $wpdb->get_results( "SELECT * FROM {$table_name}", OBJECT );

    $create_path = 'admin.php?page=create_product';
    $create_url = admin_url($create_path);

    //echo '<pre>';
    //var_dump($results);

    //echo "<h2> ".__("Cadastro de Produtos",Product::TEXT_DOMAIN)." </h2>";

   
    //$create_link = "<a href='{$create_url}'>".__("Adicionar Novo",Product::TEXT_DOMAIN)."</a>";
    //echo $create_link;

    //echo '<p><a href="createProductWarranty()"><button>Criar</button></a></p>';

    //$timezone = new DateTimeZone('America/Sao_Paulo');

    //$dt1 = new DateTime("now");
    //$dt1->setTimezone($timezone);

    //echo '<pre>';
    //var_dump(date('Y-m-d H:i:s'));
    //var_dump(current_time(date('Y-m-d H:i:s')));
    //var_dump($dt1->format('Y-m-d H:i:s'));
    
  

    ?>


      <section class="dash">
        <div class="container">
          <div class="row">
            <div class="col-12">
              
              <div class="title1 bottom2">
                <h2><?php echo __("Cadastro de Produtos",Product::TEXT_DOMAIN); ?></h2>
              </div>

              <div class="bottom2">
                <a href="<?php echo $create_url; ?>"><?php echo __("Adicionar Novo",Product::TEXT_DOMAIN); ?></a>
              </div>

              
                              
                    

                <?php



                  $html = '';
                  $edit_path = '';
                  $edit_url = '';

                  foreach( $results as $row )
                  {

                    /*
                    $html = '<p>'. $row['id'] .'</p>';
                    $html .= '<p>'. $row['serial_number'] .'</p>';
                    $html .= '<p>'. $row['warranty_term'] .'</p>';
                    $html .= '<p>'. $row['product_posted'] .'</p>';
                    $html .= '<p>'. $row['product_modified'] .'</p>';
                    */

                    $edit_path = "options.php?page=edit_product";
                    $edit_url = admin_url($edit_path);

                    $delete_path = "options.php?page=delete_product";
                    $delete_url = admin_url($delete_path);
                    
                    $create_date = new DateTime($row->product_date);
                    $update_date = new DateTime($row->product_modified);

                    ?>

                      


                      <div class="card">

                      
                        <div class="card-header bottom3">
                          <h2><?php echo $row->id . '- ' . $row->product; ?></h2>
                        </div>

                        <div class="card-body">
                          <p><span class="bold1"><?php echo __("No de Série: ",Product::TEXT_DOMAIN); ?></span><?php echo $row->serial_number; ?></p>


                          <p><span class="bold1"><?php echo __("Garantia: ",Product::TEXT_DOMAIN); ?></span><?php echo $row->warranty_term . (((int)$row->warranty_term === 1)?' dia':' dias'); ?></p>

                          <p><span class="bold1"><?php echo __("Criado em: ",Product::TEXT_DOMAIN); ?></span><?php echo $update_date->format('d/m/Y') . ' às ' . $create_date->format('H:i'); ?></p>

                          <p><span class="bold1"><?php echo __("Modificado em: ",Product::TEXT_DOMAIN); ?></span><?php echo $update_date->format('d/m/Y') . ' às ' . $update_date->format('H:i'); ?></p>
                          
                        </div>


                        

                        <div class="card-footer top2">
                          <p>
                            <a href='<?php echo $edit_url; ?>&id=<?php echo $row->id; ?>'><button class="button1 pointer"><?php echo __('Editar',Product::TEXT_DOMAIN); ?></button></a>

                            <a onclick='return confirm("Deseja realmente excluir este ítem?")' href='<?php echo $delete_url; ?>&id=<?php echo $row->id; ?>'><button class="button2 pointer"><?php echo __('Deletar',Product::TEXT_DOMAIN); ?></button></a>
                          </p>
                        </div>
                      
                      
                      </div>

                    <?php

                  }//end foreach
              


                ?>

                   
                      
                  
            </div>
          </div>
        </div><!--container-->
      </section>

    <?php


  }//end mehthod













































  public function create()
  {

    global $wpdb;

    if(
      
      isset($_POST['serial_number'])
      &&
      $_POST['serial_number'] != ''
      &&
      isset($_POST['warranty_term'])
      &&
      $_POST['warranty_term'] != ''
      
    )
    {

      $timezone = new DateTimeZone('America/Sao_Paulo');

      $create_date = new DateTime("now");
      $create_date->setTimezone($timezone);

      //echo '<pre>';
      //var_dump($create_date->format('d/m/Y H:i:s'));


      
      $wpdb->insert(

        $wpdb->prefix . Product::TABLE_NAME, 

        array(

          'product' => $_POST['product'],
          'serial_number' => $_POST['serial_number'],
          'warranty_term' => $_POST['warranty_term'],
          'product_modified' => $create_date->format('Y-m-d H:i:s'),
          'product_date' => $create_date->format('Y-m-d H:i:s')
        ), 

        array( 
          '%s',
          '%d',
          '%d', 
          '%s', 
          '%s'
        )

      );

      Product::list();
      exit;

    }//end if




    $home_path = 'admin.php?page=product';
    $home_url = admin_url($home_path);
    //$home_link = "<a href='{$home_url}'>".__("Voltar",Product::TEXT_DOMAIN)."</a>";


    ?>

    
    <section class="dash">
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-12">
            
            <div class="title1 bottom2">
              <h2><?php echo __("Criar Produto",Product::TEXT_DOMAIN); ?></h2>
            </div>

            <div class="bottom2">
              <a href="<?php echo $home_url; ?>"><?php echo __("Voltar",Product::TEXT_DOMAIN); ?></a>
            </div>

            <form method="post">


              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="serial_number_label">Nome do Produto</span>
                </div>
                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="product" name="product" id="product">
              </div>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="serial_number_label">No de Série</span>
                </div>
                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="serial_number" name="serial_number" id="serial_number">
              </div>


              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="warranty_term_label">Garantia (dias)</span>
                </div>
                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="warranty_term" name="warranty_term" id="warranty_term">
              </div>
              
              <input class="button1 top2 pointer" type="submit" value="Salvar">

            </form>
          </div>
        </div>
      </div>
    </section>


    <?php



  }//end method



























  












  public function edit()
  {

    global $wpdb;

    //echo '<pre>';
    //var_dump($_GET);
    //var_dump($wpdb);

    

    $table_name = $wpdb->base_prefix . Product::TABLE_NAME;



    if(
      
      isset($_GET['page'])
      &&
      $_GET['page'] == 'edit_product'
      &&
      isset($_GET['id'])
      &&
      $_GET['id'] != ''
      &&
      (int)$_GET['id'] > 0
      
    )
    {




      $home_path = 'admin.php?page=product';
      $home_url = admin_url($home_path);
      //$home_link = "<a href='{$home_url}'>".__("Voltar",Product::TEXT_DOMAIN)."</a>";
      //echo $home_link;





      $id = $_GET['id'];

      $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );

      $count = $wpdb->last_result[0]->{'COUNT(*)'};

      $results = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE id = $id", OBJECT );

     

    


      //echo '<pre>';
      //var_dump($count);
      //echo '<br><br>';
      //var_dump($results[0]);
      //echo '<br><br>';
      //var_dump($results[0]->id);
      //var_dump($id);
      //var_dump($results[0]->serial_number);
      //var_dump($results[0]->warranty_term);
      //var_dump($results[0]->product_modified);
      //var_dump($results[0]->product_date);
      //echo '<br><br>';
      //var_dump($wpdb->last_result[0]->id);
      //echo '<br><br>';
      //var_dump($wpdb);

      
      
      //echo '<br><br>';
      

      ?>

      

      <section class="dash">
        <div class="container">
          <div class="row">
            <div class="col-md-6 col-12">
              
              <div class="title1 bottom2">
                <h2><?php echo __("Editar Produto",Product::TEXT_DOMAIN); ?></h2>
              </div>

              <div class="bottom2">
                <a href="<?php echo $home_url; ?>"><?php echo __("Voltar",Product::TEXT_DOMAIN); ?></a>
              </div>

              <form action="options.php?page=update_product" method="post">


                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="serial_number_label">Nome do Produto</span>
                  </div>
                  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="product" name="product" id="product" value="<?php echo $results[0]->product; ?>">
                </div>

                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="serial_number_label">No de Série</span>
                  </div>
                  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="serial_number" name="serial_number" id="serial_number" value="<?php echo $results[0]->serial_number; ?>">
                </div>


                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="warranty_term_label">Garantia (dias)</span>
                  </div>
                  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="warranty_term" name="warranty_term" id="warranty_term" value="<?php echo $results[0]->warranty_term; ?>">
                </div>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input class="button1 top2 pointer" type="submit" value="Salvar">

              </form>
            </div>
          </div>
        </div>
      </section>


      <?php

      
    }//end if
    else
    {

      Product::list();
      exit;

    }//end else
    
    /*
    echo '<pre>';
      var_dump($_POST);
  
      var_dump(
  
        isset($_POST['serial_number'])
        &&
        $_POST['serial_number'] != ''
        &&
        isset($_POST['warranty_term'])
        &&
        $_POST['warranty_term'] != ''
      );
      */

      
    
  

  }//end method








































  

  public function update()
  {

    global $wpdb;

    


    if(
    
      isset($_POST['serial_number'])
      &&
      $_POST['serial_number'] != ''
      &&
      isset($_POST['warranty_term'])
      &&
      $_POST['warranty_term'] != ''
      &&
      isset($_POST['id'])
      &&
      $_POST['id'] != ''
      &&
      (int)$_POST['id'] > 0
      
    )
    {

      //echo '<pre>';
      //var_dump($_POST);
      $id = $_POST['id'];

      $table_name = $wpdb->base_prefix . Product::TABLE_NAME;

      $results = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE id = $id", OBJECT );

      //var_dump($results[0]->product_date);

      $timezone = new DateTimeZone('America/Sao_Paulo');

      $update_date = new DateTime("now");
      $update_date->setTimezone($timezone);


      $handler = $wpdb->update(

        $table_name,
        //$wpdb->prefix . Product::TABLE_NAME,

        array(

          'product' => $_POST['product'], 
          'serial_number' => $_POST['serial_number'], 
          'warranty_term' => $_POST['warranty_term'],
          'product_modified' => $update_date->format('Y-m-d H:i:s'),
          'product_date' => $results[0]->product_date

        ), 

        array( 'id' => $id ),

        array( 
          '%s',	
          '%d',	
          '%d',	
          '%s',	
          '%s',	
        ), 

        array( '%d' )

      );


      if( $handler == false )
      {

        $html = '<h3>';
        $html .= __('Houve uma falha momentânea, por favor, carreue a página novamente',Product::TEXT_DOMAIN);
        $html .= '</h3>';

        echo $html;
        exit;

      }//end if


      Product::list();
      //wp_redirect('admin.php?page=product');
      exit;
      
      
    }//end if
    else
    {

      //wp_redirect('admin.php?page=edit_product&id='.$id);
      Product::list();
      exit;

    }//end if
    
  

  }//end method
















































  public function delete()
  {

    global $wpdb;




    if(
    
      isset($_GET['page'])
      &&
      $_GET['page'] == 'delete_product'
      &&
      isset($_GET['id'])
      &&
      $_GET['id'] != ''
      &&
      (int)$_GET['id'] > 0
      
    )
    {

      //echo '<pre>';
      //var_dump($_POST);
      //var_dump($wpdb->base_prefix);
      $id = $_GET['id'];

      $table_name = $wpdb->base_prefix . Product::TABLE_NAME;

      //var_dump($table_name);

      $timezone = new DateTimeZone('America/Sao_Paulo');

      $update_date = new DateTime("now");
      $update_date->setTimezone($timezone);


      $wpdb->delete( 
        
        $table_name, 
        
        array( 'id' => $id ), 
        
        array( '%d' ) 
      
      );


      /*
      if( $handler == false )
      {

        $html = '<h3>';
        $html .= __('Houve uma falha momentânea, por favor, carreue a página novamente',Product::TEXT_DOMAIN);
        $html .= '</h3>';

        echo $html;
        exit;

      }//end if
      */


      Product::list();
      //wp_redirect('admin.php?page=product');
      exit;
      
      
    }//end if
    else
    {

      //wp_redirect('admin.php?page=edit_product&id='.$id);
      Product::list();
      exit;

    }//end if
    
  

  }//end method






































  public function search()
  {

    global $wpdb;

    echo '<pre>';
    var_dump($_POST);
    var_dump($wpdb);


    
    
    
  

  }//end method














 



// Register Custom Parceiro
public static function product_register() {

	$labels = array(
		'name'                  => _x( 'Página de Busca', 'Página de Busca Nome Geral', Product::TEXT_DOMAIN ),
		'singular_name'         => _x( 'Página de Busca', 'Página de Busca Nome Singular', Product::TEXT_DOMAIN ),
		'menu_name'             => __( 'Página de Busca', Product::TEXT_DOMAIN ),
		'name_admin_bar'        => __( 'Página de Busca', Product::TEXT_DOMAIN ),
		'archives'              => __( 'Arquivos', Product::TEXT_DOMAIN ),
		'attributes'            => __( 'Atributos', Product::TEXT_DOMAIN ),
		'parent_item_colon'     => __( 'Página Pai:', Product::TEXT_DOMAIN ),
		'all_items'             => __( 'Todos as Páginas', Product::TEXT_DOMAIN ),
		'add_new_item'          => __( 'Adicionar Nova Página', Product::TEXT_DOMAIN ),
		'add_new'               => __( 'Adicionar Nova', Product::TEXT_DOMAIN ),
		'new_item'              => __( 'Nova Item', Product::TEXT_DOMAIN ),
		'edit_item'             => __( 'Editar Página', Product::TEXT_DOMAIN ),
		'update_item'           => __( 'Atualizar Página', Product::TEXT_DOMAIN ),
		'view_item'             => __( 'Ver Página', Product::TEXT_DOMAIN ),
		'view_items'            => __( 'Ver Páginas', Product::TEXT_DOMAIN ),
		'search_items'          => __( 'Buscar Páginas', Product::TEXT_DOMAIN ),
		'not_found'             => __( 'Nada Foi Encontrado', Product::TEXT_DOMAIN ),
		'not_found_in_trash'    => __( 'Nada Foi Encontrado Na Lixeira', Product::TEXT_DOMAIN ),
		'featured_image'        => __( 'Imagem Destacada', Product::TEXT_DOMAIN ),
		'set_featured_image'    => __( 'Configurar Imagem Destacada', Product::TEXT_DOMAIN ),
		'remove_featured_image' => __( 'Remover Imagem Destacada', Product::TEXT_DOMAIN ),
		'use_featured_image'    => __( 'Usar como Imagem Destacada', Product::TEXT_DOMAIN ),
		'insert_into_item'      => __( 'Inserir na Página', Product::TEXT_DOMAIN ),
		'uploaded_to_this_item' => __( 'Carregado Para a Página', Product::TEXT_DOMAIN ),
		'items_list'            => __( 'Lista de Páginas', Product::TEXT_DOMAIN ),
		'items_list_navigation' => __( 'Navegação na Lista de Páginas', Product::TEXT_DOMAIN ),
		'filter_items_list'     => __( 'Filtrar Lista de Páginas', Product::TEXT_DOMAIN ),
	);
	$args = array(
		'label'                 => __( 'Página de Busca', Product::TEXT_DOMAIN ),
		'description'           => __( 'Página onde ficará o formulário de Busca do Produto', Product::TEXT_DOMAIN ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail','page-attributes' ),
		'taxonomies'            => array( Product::TAXONOMY ),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 21,
		'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => true,
    'menu_icon'             =>'dashicons-code-standards',
		'can_export'            => true,
    'has_archive'           => true,
    //'rewrite'               => array('slug'=>'/','with_front'=>false),
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( Product::TEXT_DOMAIN, $args );

}//end function
//add_action( 'init', 'product', 0 );

























//CaMPOS PERSONALIZADOS
public static function get_meta_box( $meta_boxes ) {
	$prefix = 'pw-';

	$meta_boxes[] = array(
		'id' => 'geral',
		'title' => esc_html__( 'Informações Gerais', Product::TEXT_DOMAIN ),
		'post_types' => array(Product::TEXT_DOMAIN ),
		'context' => 'advanced',
		'priority' => 'default',
		'autosave' => 'true',
		'fields' => array(
			array(
				'id' => $prefix . 'vip',
				'name' => esc_html__( 'Usuário VIP', Product::TEXT_DOMAIN ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Este parceiro é VIP ou não?', Product::TEXT_DOMAIN ),
			),

			array(
				'id' => $prefix . 'endereco',
				'type' => 'textarea',
				'name' => esc_html__( 'Endereço', Product::TEXT_DOMAIN ),
				'desc' => esc_html__( 'Endereço do Parceiro', Product::TEXT_DOMAIN ),
				'placeholder' => esc_html__( 'Endereço do Parceiro', Product::TEXT_DOMAIN ),
				'rows' => 4,
				'cols' => 1,
			),

			array(
				'id' => $prefix . 'cidade',
				'type' => 'text',
				'name' => esc_html__( 'Cidade', Product::TEXT_DOMAIN ),
				'desc' => esc_html__( 'Cidade do Parceiro', Product::TEXT_DOMAIN ),
				'placeholder' => esc_html__( 'Cidade do Parceiro', Product::TEXT_DOMAIN ),
				'size' => 40,
			),
			array(
				'id' => $prefix . 'email',
				'name' => esc_html__( 'Email', Product::TEXT_DOMAIN ),
				'type' => 'email',
				'desc' => esc_html__( 'Email do Parceiro', Product::TEXT_DOMAIN ),
				'placeholder' => esc_html__( 'Email do Parceiro', Product::TEXT_DOMAIN ),
				'clone' => 'true',
				'size' => 40,
				'sort_clone' => 'true',
				'max_clone' => 20,
			),

			array(
				'id' => $prefix . 'telefone',
				'type' => 'text',
				'name' => esc_html__( 'Telefone', Product::TEXT_DOMAIN ),
				'desc' => esc_html__( 'Telefone do Parceiro', Product::TEXT_DOMAIN ),
				'placeholder' => esc_html__( 'Telefone do Parceiro', Product::TEXT_DOMAIN ),
				'size' => 40,
				'clone' => 'true',
				'max_clone' => 20,
				'sort_clone' => 'true',
			),

		),

		'validation' => array(
			'rules'  => array(
				'pw-endereco' => array(
					'required'  => true,
				),
				'pw-cidade' => array(
					'required'  => true,
				),
				'pw-email[0]' => array(
					'required'  => true,
					'email'=>true,
				),
				
				// Rules for other fields
			),
		),
		


	);




	$meta_boxes[] = array(
		'id' => 'imagem',
		'title' => esc_html__( 'Imagem', Product::TEXT_DOMAIN ),
		'post_types' => array(Product::TEXT_DOMAIN ),
		'context' => 'advanced',
		'priority' => 'default',
		'autosave' => 'true',
		'fields' => array(
			array(
				'id' => $prefix . 'imagem',
				'type' => 'image_advanced',
				'name' => esc_html__( 'Galeria de Imagens', Product::TEXT_DOMAIN ),
				'desc' => esc_html__( 'Galeria de Imagens', Product::TEXT_DOMAIN ),
				'max_file_uploads' => '20',
				'force_delete' => 'true',
			),			

		),
	);

	return $meta_boxes;
}
//add_filter( 'rwmb_meta_boxes', 'get_meta_box' );





























// Register Custom Taxonomy
public static function product_category_register() {

	$labels = array(
		'name'                       => _x( 'Finalidade da Página', 'Finalidade da Página Nome Geral', Product::TEXT_DOMAIN ),
		'singular_name'              => _x( 'Finalidade da Página', 'Finalidade da Página Nome Singular', Product::TEXT_DOMAIN ),
		'menu_name'                  => __( 'Finalidade da Página', Product::TEXT_DOMAIN ),
		'all_items'                  => __( 'Todos os Produtos', Product::TEXT_DOMAIN ),
		'parent_item'                => __( 'Finalidade Pai', Product::TEXT_DOMAIN ),
		'parent_item_colon'          => __( 'Finalidade Pai:', Product::TEXT_DOMAIN ),
		'new_item_name'              => __( 'Novo Nome Pra Finalidade', Product::TEXT_DOMAIN ),
		'add_new_item'               => __( 'Adicionar Nova Finalidade', Product::TEXT_DOMAIN ),
		'edit_item'                  => __( 'Editar Finalidade', Product::TEXT_DOMAIN ),
		'update_item'                => __( 'Atualizar Finalidade', Product::TEXT_DOMAIN ),
		'view_item'                  => __( 'Ver Finalidade', Product::TEXT_DOMAIN ),
		'separate_items_with_commas' => __( 'Separar Finalidades Com Vírgulas', Product::TEXT_DOMAIN ),
		'add_or_remove_items'        => __( 'Adicionar ou Remover Finalidades', Product::TEXT_DOMAIN ),
		'choose_from_most_used'      => __( 'Escolher Entre as Mais Usados', Product::TEXT_DOMAIN ),
		'popular_items'              => __( 'Finalidades Populares', Product::TEXT_DOMAIN ),
		'search_items'               => __( 'Buscar Finalidades', Product::TEXT_DOMAIN ),
		'not_found'                  => __( 'Nada Foi Encontrado', Product::TEXT_DOMAIN ),
		'no_terms'                   => __( 'Não Há Finalidades Cadastradas', Product::TEXT_DOMAIN ),
		'items_list'                 => __( 'Lista de Finalidades', Product::TEXT_DOMAIN ),
		'items_list_navigation'      => __( 'Navegação Pela Lista de Finalidades', Product::TEXT_DOMAIN ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( Product::TAXONOMY, array( Product::TEXT_DOMAIN ), $args );

}
//add_action( 'init', 'product_category', 0 );






















 
/*

  
public static function register_post_type()
{

  register_post_type(
      
    Product::TEXT_DOMAIN,

    array(


      'labels'=>array(

        'name'=>'Product Warranty',
        'singular_name'=>'Product Warranty'

      ),

      'description'=>'Front-End do Cadastro de Produtos',

      'supports'=>array(

        'title',
        'editor',
        'excerpt',
        'author',
        'revisions',
        'thumbnail',
        'custom-fields'

      ),

      'public'=>true,

      'menu_icon'=>'dashicons-yes',

      'menu_position'=>21,

      'rewrite'=> array(

        'slug'=>'/',
        'with_front'=>false

      )


    )//end array
  
  );//end register_post_type

}//END register_post_type





  public static function register_taxonomies()
  {

      register_taxonomy(

          'product',

          array(

            Product::TEXT_DOMAIN
            //Product::TEXT_DOMAIN
            
          ),

          array(

            'labels'=> array(

              'name'=>__( 'Tipos de Produto' ),
              'singular_name'=>__( 'Tipo de PRoduto' )
                
            ),

            'public'=>true,

            'hierarchical'=>true,

            'rewrite'=> array(

              'slug'=>Product::TEXT_DOMAIN

            )

          )

      );//end register_taxonomy


  }//END register_taxonomies










  public function metabox_custom_fields()
  {

      $meta_boxes[] = array(

          'id'=>'data_filme',

          'title'=> __('Informações Adicionais', Product::TEXT_DOMAIN),

          'pages'=> array(

              Product::TEXT_DOMAIN,
              
              'post'
          
          ),
          
          'context'=>'normal',

          'priority'=>'high',
          
          'fields'=> array(

              array(

                  'name' => __('Ano de laçamento',Product::TEXT_DOMAIN),
                  'desc' => __('Ano em que o filme foi lançano',Product::TEXT_DOMAIN),
                  'id'   => Product::FIELD_PREFIX . 'filme_ano',
                  'type' => 'number',
                  'std'  => date('Y'),
                  'min'  => '1880'

              ),

              array(

                  'name' => __('Diretor',Product::TEXT_DOMAIN),
                  'desc' => __('Quem dirigiu o filme',Product::TEXT_DOMAIN),
                  'id'   => Product::FIELD_PREFIX . 'filme_diretor',
                  'type' => 'text',
                  'std' => ''

              ),

              array(

                  'name' => 'Site',
                  'desc' => 'Link do site do filme',
                  'id'   => Product::FIELD_PREFIX . 'filme_site',
                  'type' => 'url',
                  'std'  => ''

              )         

          )

      );


      $meta_boxes[] = array(

          'id'        => 'warranty_data',

          'title'     => __('Product Warranty',Product::TEXT_DOMAIN),

          'pages'     => array( Product::TEXT_DOMAIN ),

          'context'   => 'side',

          'priority'  => 'high',

          'fields'    => array(

              array(

                  'name' => __( 'Classificação:',Product::TEXT_DOMAIN ),

                  'desc' => __('Em uma escala de 1 - 10 , sendo que 10 é a melhor nota',Product::TEXT_DOMAIN),

                  'id'   => Product::FIELD_PREFIX . Product::REVIEW_RATING,

                  'type' => 'select',

                  'options' => array(

                      '' => __('Avalie Aqui',Product::TEXT_DOMAIN),
                      '1'  => __('1 - Gostei um pouco',Product::TEXT_DOMAIN),
                      '2'  => __('2 - Eu gostei mais ou menos',Product::TEXT_DOMAIN),
                      '3'  => __('3 - Não recomendo',Product::TEXT_DOMAIN),
                      '4'  => __('4 - Deu pra assistir tudo',Product::TEXT_DOMAIN),
                      '5'  => __('5 - Filme decente',Product::TEXT_DOMAIN),
                      '6'  => __('6 - Filme legal',Product::TEXT_DOMAIN),
                      '7'  => __('7 - Legal, recomendo',Product::TEXT_DOMAIN),
                      '8'  => __('8 - O meu favorito',Product::TEXT_DOMAIN),
                      '9'  => __('9 - Amei um dos meus melhores filmes',Product::TEXT_DOMAIN),
                      '10' => __('10 - O melhor filme de todos os tempos, recomendo!!',Product::TEXT_DOMAIN)

                  ), 

                  'std' => ''
              
              )
          
          )     
      
      );

      return $meta_boxes;

  }//END metabox_custom_fields


*/




















    public function check_required_plugins()
    {

        $plugins = [

            [

                'name'=>'Meta Box',
                'slug'=>'meta-box',
                'required'=>true,
                'force_activation'=>false,
                'force_desactivation'=>false,

            ]

        ];//end $plugins


        $config  = array(

            'domain'           => Product::TEXT_DOMAIN,

            'default_path'     => '',
            
            'parent_slug'      => 'plugins.php',
            
            'capability'       => 'update_plugins',
            
            'menu'             => 'install-required-plugins',
            
            'has_notices'      => true,
            
            'is_automatic'     => false,
            
            'message'          => '',
            
            'strings'          => array(

                'page_title'                      => __( 'Instalar plugins requeridos', Product::TEXT_DOMAIN ),

                'menu_title'                      => __( 'Instalar Plugins', Product::TEXT_DOMAIN),

                'installing'                      => __( 'Instalando o Plugin: %s', Product::TEXT_DOMAIN),

                'oops'                            => __( 'Algo deu errado com a API do plugin.', Product::TEXT_DOMAIN ),

                'notice_can_install_required'     => _n_noop( 'O plugin Movie Reviews depende do seguinte plugin: %1$s.', 'O plugin Movie Reviews depende do seguinte plugins: %1$s.' ),

                'notice_can_install_recommended'  => _n_noop( 'O plugin Movie Reviews recomenda o seguinte plugin: %1$s.', 'O plugin Movie Reviews recomenda o seguinte plugins: %1$s.' ),

                'notice_cannot_install'           => _n_noop( 'Desculpe, mas você não tem as permissões corretas para instalar o plugin %s. Entre em contato com o administrador deste site para obter ajuda sobre como instalar o plugin.', 'Desculpe, mas você não tem as permissões corretas para instalar os plugins %s. Entre em contato com o administrador deste site para obter ajuda sobre como instalar os plugins.' ),

                'notice_can_activate_required'    => _n_noop( 'O plugin requerido está inativo no momento: %1$s.', 'Os plugins requeridos estão inativos no momento: %1$s.' ),

                'notice_can_activate_recommended' => _n_noop( 'O seguinte plugin recomendado está inativo no momento: %1$s.', 'Os seguintes plugins recomendados estão inativos no momento: %1$s.' ),

                'notice_cannot_activate'          => _n_noop( 'Desculpe, mas você não tem as permissões corretas para ativar o plugin %s. Entre em contato com o administrador deste site para obter ajuda sobre como ativar o plugin.', 'Desculpe, mas você não tem as permissões corretas para ativar os plugins %s. Entre em contato com o administrador deste site para obter ajuda sobre como ativar os plugins.' ),

                'notice_ask_to_update'            => _n_noop( 'O seguinte plugin precisa ser atualizado para sua versão mais recente para garantir a máxima compatibilidade com este tema: %1$s.', 'Os seguintes plugins precisam ser atualizados para suas versões mais recentes para garantir a máxima compatibilidade com este tema: %1$s.' ),

                'notice_cannot_update'            => _n_noop( 'Desculpe, mas você não tem as permissões corretas para atualizar o plugin %s. Entre em contato com o administrador deste site para obter ajuda sobre como atualizar o plugin.', 'Desculpe, mas você não tem as permissões corretas para atualizar os plugins %s. Entre em contato com o administrador deste site para obter ajuda sobre como atualizar os plugins.' ),

                'install_link'                    => _n_noop( 'Começar a instalar plugin', 'Começar a instalar plugins' ),

                'activate_link'                   => _n_noop( 'Ativar plugin instalado', 'Ativar plugins instalados' ),

                'return'                          => __( 'Retornar ao Instalador de Plugins Requeridos', Product::TEXT_DOMAIN ),

                'plugin_activated'                => __( 'Plugin ativado com sucesso', Product::TEXT_DOMAIN ),

                'complete'                        => __( 'Todos os plugins instalados e ativados com sucesso. %s', Product::TEXT_DOMAIN ),

                'nag_type'                        => 'updated',
            
            )//end array

        );//end array

        tgmpa( $plugins, $config );

    }//END check_required_plugins







    public static function activate()
    {

        //Product::register_post_type();

        //Product::register_taxonomies();

        flush_rewrite_rules();
        

    }//END activate


  




}//end class



Product::getInstance();

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
register_activation_hook( __FILE__, 'Product::activate' );