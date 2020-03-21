<div id="primary" class="content-area">

  <main id="main" class="content site-main" role="main">

<?php
    
    /** While */
    while( have_posts() ): the_post();

      $campo_prefixo = Product_Warranty::FIELD_PREFIX;

      $imagem   = get_the_post_thumbnail(get_the_ID(),'medium');

      $imagem_url   = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()),'medium'); //RECUPERAR O THUMBNAIL

      $rating   = (int)post_custom($campo_prefixo. 'review_rating');

      $exibir_rating = mostrar_rating($rating); /*mostrar rating função para exibir dash icons*/

      $diretor = wp_strip_all_tags( post_custom($campo_prefixo.'filme_diretor'));

      $link_site = esc_url(post_custom($campo_prefixo.'filme_site'));

      $ano = (int)post_custom($campo_prefixo.'filme_ano');

      $filmes_categoria   = get_the_terms(get_the_ID(),'tipos_filmes');
      
      $filme_tipo    = '';

      if( $filmes_categoria && !is_wp_error($filmes_categoria) ):

        $filme_tipo = array();

        foreach( $filmes_categoria as $cat ):

          $filme_tipo[] = $cat->name;

        endforeach;

      endif;

      ?>

      <article id="post-<?php the_ID(); ?>" <?php post_class('hentry' )?>>

        <header class="entry-header">
      
          <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
      
        </header>

        <div class="entry-content">

          <div class="left">

          <?php

          if( isset($imagem) ):

            ?>
          
            <div class="poster">

                <?php

                if(isset($link_site)):
                
                  ?>
              
                    <?php echo '<a href="'.$link_site.'" target="__blank"> '.$imagem.' </a>'; ?>
              
                  <?php

                  else:

                    echo $imagem;

                endif; 
            
              endif;

              ?>

              <?php if(!empty($exibir_rating)): ?>
            
                <div class="rating rating-<?php echo $rating; ?>">

                  <?php echo $exibir_rating; ?>

                </div><!-- END rating rating -->

              <?php endif; ?>

              <div class="filme-meta">

                <?php if(!empty($diretor)): ?>

                  <label>Dirigido por: </label><?php echo $diretor; ?>

                <?php endif; ?>

                <?php if(!empty($filmes_categoria)): ?>

                  <div class="tipo">

                    <label>Genero:</label>
              
                    <?php 
                
                    foreach ($filme_tipo as  $t):
                    
                      echo ' / '.$t;
                
                    endforeach;

                    ?>

                  </div><!--END tipo -->

                <?php endif; ?>

                <?php if(!empty($ano)): ?>

                  <div class="lacamento-ano">

                    <label>Ano:</label>
                
                    <?php 
                
                    echo $ano; 
                
                    ?>
                
                  </div><!-- END lacamento -->
              
                <?php endif; ?>

                <?php if(!empty($link_site)): ?>

                  <div class="link">

                    <label>Site:</label>
                  
                    <?php
                  
                    echo '<a href="'.$link_site.'" target="__blank"> Visite o site </a>'; 
                  
                    ?>
                
                  </div><!-- END link-->
              
                <?php endif; ?>

                <div class="right">

                  <div class="review-body">

                    <?php the_content(); ?>
                    
<h1>fçlkjafçlkajsflçksajfçlksjfça</h1>


                  </div><!-- END review-body -->

                </div><!--END right -->

              </div><!-- END filme-meta -->

            </div><!-- END poster -->

          </div><!-- END left -->

        </div><!-- END entry-content -->

        <?php

        edit_post_link(__('Editar'),'<footer class="entry-footer"><span class="edit-link">', '</span> </footer>');

        ?>

      </article> 
      
      <?php 
      
      /*Comentarios*/
      if(comments_open() || get_comments_number()):

      comments_template();

      endif;

      /*NAVEGAÇÃO*/
      the_post_navigation( array(

        'next_text' => '<span class="meta-nav" aria-hidden="true">'.__('Próximo').'</span>'.
          '<span class="screen-reader-text">'.__('Próximo Review').'</span>'.
          '<span class="post-title"> %title </span>',

        'prev_text' => '<span class="meta-nav" aria-hidden="true">'.__('Anterior').'</span>'.
          '<span class="screen-reader-text">'.__('Review Anterior').'</span>'.
          '<span class="post-title"> %title </span>',   

      ));//end the_post_navigation

      ?>

    <?php endwhile; ?> 


    </main><!-- END main-->

</div><!-- END primary-->