<template><div>
   <top-header page_role="0" ></top-header>
   <!--<index/>-->
   <!--<AddDocument/>-->

   <!-- ##### Hero Area Start ##### -->
   <section class="hero-area" style="border:0px red solid; padding:10px">

      <!--<div class="preloader d-flex align-items-center justify-content-center">-->
         <!--<div class="circle-preloader">-->
            <!--<div class="a" style="&#45;&#45;n: 5;">-->
               <!--<div class="dot" style="&#45;&#45;i: 0;"></div>-->
               <!--<div class="dot" style="&#45;&#45;i: 1;"></div>-->
               <!--<div class="dot" style="&#45;&#45;i: 2;"></div>-->
               <!--<div class="dot" style="&#45;&#45;i: 3;"></div>-->
               <!--<div class="dot" style="&#45;&#45;i: 4;"></div>-->
            <!--</div>-->
         <!--</div>-->
      <!--</div>-->

      <div class="hero-post-slides owl-carousel" style="display: block !important;">
         <!--<div class="hero-post-slides owl-carousel">   -->

         <!-- Single Hero Post -->
         <div class="single-hero-post d-flex flex-wrap">
            <!-- Post Thumbnail -->
            <div class="slide-post-thumbnail h-100 bg-img" style="background-image: url(img/blog-img/home-index.jpg);"></div>
            <!-- Post Content -->
            <div class="slide-post-content h-100 d-flex align-items-center" style="display: block !important;">
               <div class="slide-post-text">
                  <p class="post-date" data-animation="fadeIn" data-delay="100ms">MAY 01, 2020 / lifestyle</p>
                  <a href="#" class="post-title" data-animation="fadeIn" data-delay="300ms">
                     <h2>Приветствуем Вас на сервисе электронной подписи документов</h2>
                     <h2 style="font-weight: bold" >Faximile Service</h2>
                  </a>
                  <p class="post-excerpt" data-animation="fadeIn" data-delay="500ms">
                     Для того чтобы пользоваться сервисом, нужно авторизироваться</p>
                  <!--<a href="#" class="btn nikki-btn" data-animation="fadeIn" data-delay="700ms">Войти в сервис</a>-->
                  <router-link to="/page/auth" class="btn nikki-btn" data-animation="fadeIn" >
                     Войти в сервис
                  </router-link> <p></p>

                  <router-link to="/page/register" class="btn nikki-btn" data-animation="fadeIn" >
                     Регистрация
                  </router-link>

               </div>

               <!-- Page Count -->
               <div class="page-count"></div>

            </div>
         </div>

      </div>
   </section>
   <!-- ##### Hero Area End ##### -->



</div></template>

<script>
  //import Index from '../components/Index'
  export default {
    name: 'Home',
    //components: { Index },
  }
</script>
