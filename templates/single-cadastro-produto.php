<?php get_header(); ?>

  <section class="site">
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-12">

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

          global $wpdb;

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

          $html = '';

      ?>


        <div class="row">
          <div class="col-12">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Produto</th>
                    <th scope="col">No Serial</th>
                    <th scope="col">Garantia</th>
                    <th scope="col">Modificado em</th>
                    <th scope="col">Criado em</th>
                  </tr>
                </thead>
                <tbody>
                  

                  <?php

                    foreach($results as $row)
                    {
                      
                      //$days = ((int)$row->warranty_term === 1)?' dia':' dias';
                      $dt_posted = new DateTime($row->product_warranty_date);
                      $dt_modified = new DateTime($row->product_warranty_modified);

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

      <?php }//end if ?>

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