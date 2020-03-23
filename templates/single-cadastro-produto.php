<?php get_header(); ?>

  <?php
  
    global $wpdb;

    $table_name = $wpdb->base_prefix . Product::TABLE_NAME;
    //$serial_number = $_POST['serial_number'];

    //echo '<pre>';
    //echo '<h2>Front</h2>';
    //var_dump($_POST);
    //var_dump($serial_number);
    //var_dump($table_name);
    //var_dump($wpdb->prefix);
    //var_dump($wpdb->base_prefix);
    //var_dump($wpdb);

    $results2 = $wpdb->get_results( "SELECT * FROM {$table_name}", OBJECT );

    //var_dump($results2);
  
  ?>

  <section class="site">
    <div class="container">


    
      <div class="row bottom2">
        <div class="col-md-8 col-12">

          <form method="post">

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" id="serial_number_label">No de Série</span>
              </div>
              <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="serial_number" name="serial_number" id="serial_number">
            </div>
            <input class="top2 bottom2 decoration-none" type="submit" value="Buscar">
          </form>
        </div>


        <div class="col-md-4 col-8">

          <div class="section1">

          <h3><?php echo __("Você pode testar a busca com um dos números de série a seguir:",Product::TEXT_DOMAIN); ?></h3>

          <ul>
          <?php
            $count = 0;
            foreach( $results2 as $row )
            {
              
              if($count < 3)
              {

                ?>

                  <li><?php echo $row->serial_number; ?></li>

                <?php

                

              }//end if
              

              $count++;

            }//end foreach
          
          ?>
          </ul>

          </div>
        </div>
        
      </div>



      <?php 
      
        if(
          
          isset($_POST['serial_number'])
          &&
          $_POST['serial_number'] != ''
          &&
          (int)$_POST['serial_number'] > 0
        
        )
        {

          //global $wpdb;

          $table_name = $wpdb->base_prefix . Product::TABLE_NAME;
          $serial_number = $_POST['serial_number'];

          //echo '<pre>';
          //echo '<h2>Front</h2>';
          //var_dump($_POST);
          //var_dump($serial_number);
          //var_dump($table_name);
          //var_dump($wpdb->prefix);
          //var_dump($wpdb->base_prefix);
          //var_dump($wpdb);

          $results = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE serial_number = $serial_number", OBJECT );

          //var_dump($results);

          $count = count($results);


          //echo '<pre>';
          //var_dump($results);
          //var_dump($count);

          $html = '';



          if( (int)$count != 0 )
          {

            ?>


              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col"><?php echo __("Produto",Product::TEXT_DOMAIN); ?></th>
                          <th scope="col"><?php echo __("No de Série",Product::TEXT_DOMAIN); ?></th>
                          <th scope="col"><?php echo __("Garantia",Product::TEXT_DOMAIN); ?></th>
                          <th scope="col"><?php echo __("Modificado em",Product::TEXT_DOMAIN); ?></th>
                          <th scope="col"><?php echo __("Criado em",Product::TEXT_DOMAIN); ?></th>
                        </tr>
                      </thead>
                      <tbody>
                        

                        <?php

                          foreach($results as $row)
                          {
                            
                            //$days = ((int)$row->warranty_term === 1)?' dia':' dias';
                            $dt_posted = new DateTime($row->product_date);
                            $dt_modified = new DateTime($row->product_modified);

                            ?>


                              <tr>
                                <td><?php echo $row->id; ?></td>
                                <td><?php echo $row->product; ?></td>
                                <td><?php echo $row->serial_number; ?></td>
                                <td><?php echo $row->warranty_term . (((int)$row->warranty_term === 1)?' dia':' dias'); ?></td>
                                <td><?php echo $dt_modified->format('d/m/Y H:i:s'); ?></td>
                                <td><?php echo $dt_posted->format('d/m/Y H:i:s'); ?></td>
                              </tr>

                            <?php


                          }//end foreach
                          
                      
                        ?>

                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div><!--row-->
              

            
          
          <?php

          }//end if
          else
          {
            
            ?>

              <div class="row">

                <div class="col-12">

                  <div class="alert alert-light alert1" role="alert">
                    <h2>

                      <?php echo __("Não foi encontrado nenhum resultado | Por favor, verifique o número digitado e tente novamente",Product::TEXT_DOMAIN); ?>

                    </h2>
                  </div>

                  

                </div>

              </div>

              


            <?php

          }//end else

       }//end if
       
       ?>

    </div><!--container-->
  </section>
      
<?php

  get_footer();

  /*Função helper mostrar_rating 

  function mostrar_rating( $rating = NULL )
  {

    $rating = (int) $rating;

    if( $rating > 0)
    {

      $estrelas_rating = array();

      $mostrar_rating = "";

      for( $i = 0 ; $i < floor($rating/2); $i++ )
      {

        $estrelas_rating[] = '<span class="dashicons dashicons-star-filled"></span>';

      }//end if

      if( $rating % 2 === 1 )
      {

        $estrelas_rating[] = '<span class="dashicons dashicons-star-half"></span>';

      }//end if

      $estrelas_rating = array_pad($estrelas_rating, 5,'<span class="dashicons 
      dashicons-star-empty"> </span>' );

      return implode("\n", $estrelas_rating);

    }//end if

    return false;

  }//END mostrar_rating


  */



?>