<style>
 
.post_container {
    float: left;
    max-width: 50%;
	   padding: 5%;
    text-align: center;
}
 </style>

<?php
echo $pid = $_REQUEST['pid'];
die;
 
	       $readpost = get_post($pid); 
            $title = $readpost->post_title;
             $content = $readpost->post_content;
	 
if( have_posts()){
	
echo '<h1>'.$title.'</h1>';
while ( have_posts() ) : the_post();

?>

<?php 
    
   $post_id = get_the_ID();
   $imgurl = wp_get_attachment_url( get_post_thumbnail_id($id ) );
   
  ?>  
  
  
  
<div class="wrapper">

<div class="post_container">

<div class="post_img_container" >
 <img src="<?php echo $imgurl;?>" width="300px"/>
</div>
<div  class="post_title lnName"><a href="read/<?php echo  $post_id;?>"><?php echo $title?></a></div>
<div class="post_excerpt"><?php $content?></div>

</div>


</div>
 
  
<?php
endwhile;
// The Loop...
}