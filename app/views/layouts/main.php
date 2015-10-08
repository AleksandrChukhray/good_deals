<!DOCTYPE html>
<html>
  <!-- Модули кросбраузерности-->
  <!--[if lte IE 8]
  <script src="/public/js/gooddeals/html5shiv/respond.min.js"></script>
  <![endif]-->
    <head>
        <title><?php echo $this->meta['meta_title']; ?></title>
        <meta http-equiv="Content-Type" content="application/json" charset="utf-8" />
        <meta charset="utf-8">
        <meta name="keywords" content="<?php echo $this->meta['meta_keywords']; ?>"> 
        <meta name="description" content="<?php echo $this->meta['meta_description']; ?>">  
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/x-icon" href="/<?php echo DIR_IMAGES; ?>favicon.ico" />
        <link rel="icon" type="image/x-icon" href="/<?php echo DIR_IMAGES; ?>favicon.ico" />
        
        <!-- Общие модули для всех разделов сайта -->
        <link href="/public/css/style.css" rel="stylesheet">
        <link href="/public/css/checkbox.css" rel="stylesheet">
        <link href="/public/css/bootstrap_alert.css" rel="stylesheet" type="text/css" />
        <link href="/public/css/flickr_ultim.css" media="screen" rel="stylesheet" type="text/css" />
        <!-- Мои модуля -->
        <link rel="stylesheet" href="/public/css/gooddeals/reset.css">
        <link rel="stylesheet" href="/public/css/gooddeals/normalize.css">
        <link rel="stylesheet" href="/public/css/gooddeals/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="/public/css/gooddeals/animate/animate.min.css">
        <link rel="stylesheet" href="/public/css/gooddeals/owl-carousel/owl.theme.css">
        <link rel="stylesheet" href="/public/css/gooddeals/owl-carousel/owl.carousel.css">
        <link rel="stylesheet" href="/public/css/gooddeals/owl-carousel/owl.transitions.css">
        <link rel="stylesheet" href="/public/css/gooddeals/calendar/zabuto_calendar.css">
        <link rel="stylesheet" href="/public/css/gooddeals/main.css">
        <link rel="stylesheet" href="/public/css/gooddeals/media.css">
        <script src="/public/js/jquery-1.11.2.min.js"></script>

        <!-- Загрузка модулей, специфических для загружаемого раздела сайта -->
        <?php
        switch ($this->path['module']) {
            case "IndexController" :
                echo '<link href="/public/css/blueberry.css" rel="stylesheet" />'; // центральный слайдер с новостями на главной станице
                break;

            case "ArticleController" :
                echo '<meta property="og:image" content="' . URL . $article->PhotoL . '" />';
                echo '<link rel="image_src" href="' . URL . $article->PhotoL . '" />';
                echo '<link href="/public/css/pagination.css" rel="stylesheet" type="text/css" />';
                echo '<link href="/public/css/article_style.css" rel="stylesheet" type="text/css" />';
                break;

            case "CategoryController" :
                echo '<link href="/public/css/pagination.css" rel="stylesheet" type="text/css" />';
                break;

            case "CatalogController" :
                echo '<link href="/public/css/style-font.css" rel="stylesheet" />';
                echo '<link href="/public/css/pagination.css" rel="stylesheet" type="text/css" />';
                echo '<link href="/public/css/catalog.css" rel="stylesheet" />';
                break;

            case "GooddealsController" :
                echo '<link href="/public/css/gooddeals.css" rel="stylesheet" type="text/css" />';
                break;

            case "MarketController" :
                echo '<script type="text/javascript" src="/public/js/noty/js/noty/packaged/jquery.noty.packaged.min.js"></script>';
                echo '<link href="/public/css/market.css" rel="stylesheet" />';
                break;
                    
				case "ConsultController" :
                    echo '<meta http-equiv = "X-UA-Compatible" content = "IE=edge, chrome=1" />';
                    echo '<link href = "/public/css/consult.css" rel = "stylesheet" />';
                    echo '<script src = "/public/js/chained_select.js"></script>';
                    echo '<script src="/public/js/smartpaginator.js"></script>';
                    echo '<script type="text/javascript">'.
                            '$(function () {'.
                                '$("#expert").chained("#category");'.
                                '$("#expert-1").chained("#category-1");'.
                                '$("#expert-2").chained("#category-2");'.
                                '$("#expert-3").chained("#category-3");'.
                                '$("#expert-4").chained("#category-4");'.
                                '$("#expert-5").chained("#category-5");'.
                            '});'.
                         '</script>';
            break;

            }
            ?>

            <script>
                (function (i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r;
                    i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                    a = s.createElement(o),
                            m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

                ga('create', 'UA-57644667-1', 'auto');
                ga('send', 'pageview');
            </script>
        </head>

        <body id="<?php echo $this->path['controller']; ?>" class="<?php echo $this->path['controller'] . '-' . $this->path['action'] . ' ' . $this->path['action']; ?>">
            <div id="site_content">
                <div id="header_background">
                    <div id="header_background_left"></div>  
                    <div id="header_background_middle"></div>  
                    <div id="header_background_right"></div>  
                </div>  

                <div id="header_center">
                    <div id="header_logo_background">
                        <a href="/" title="Карапуз"><img src="/public/images/logo_top.png" onmouseover="this.src = '/public/images/logo_top_active.png';" onmouseout="this.src = '/public/images/logo_top.png';" width="247" height="260" alt="Карапуз" /></a>
                    </div>

                    <div id="header_top_block">
                        <div id="header_top_block_login">
                            <div class="login_social">
                                <a href="https://www.facebook.com/KarapuzCommunity" class="login_social_item facebook"></a>
                                <a href="http://vk.com/club91598856" class="login_social_item vk"></a>
                            </div>

                            <?php if (!isset($_SESSION['auth'])) : ?>
                                <div class="top_registration">
                                    <a href="/auth/registration"><div class="login_block reg_link">Регистрация</div></a>
                                </div>
                                <div class="top_signin">
                                    <a href="/auth/login"><div class="login_block login_link">Войти на сайт</div></a>
                                </div>
                            <?php else : ?>
                                <div class="top_hellouser">
                                    <div class="top_hellouser_text">Здравствуйте <?php echo $_SESSION['auth']['firstname']; ?>!</div>
                                </div>

                                <div class="top_signin">
                                    <a href="/auth/logout"><div class="login_block login_link">Выйти</div></a>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>


                    <div id="header_menu">
                        <div id="header_menu_center_background">
                            <ul id="nav" class="dropdown">
                                <li><a href="/catalog/" class="no_dir">Каталог организаций</a></li>
                                <li><a href="/category/" class="no_dir">Статьи</a></li>
                                <li class="dev"><a href="/consult/" class="no_dir">Консультации</a></li>
                                <li class="dev"><a href="/market/" class="no_dir">Маркет</a></li>

                                <!--<li>
                                    <span class="dir">Разделы</span>
                                    <ul>
                                        <li class="down"><a href="/category/" class="a_down">Статьи</a></li>
                                        <li class="down"><a href="/forum/forum.php" class="a_down">Форум</a></li>
                                        <li class="down"><a href="/catalog/" class="a_down">Каталог организаций</a></li>
                                        <li class="down dev"><a href="./" class="a_down">Консультации</a></li>
                                        <li class="down dev"><a href="./" class="a_down">События в городе</a></li>
                                        <li class="down dev"><a href="./" class="a_down">Наши встречи</a></li>
                                        <li class="down dev"><a href="#" class="a_down">Все товары и услуги</a></li>
                                        <li class="down dev"><a href="#" class="a_down">Акции, скидки</a></li>
                                        <li class="down dev"><a href="#" class="a_down">Зелёная Украина</a></li>                                    
                                    </ul>
                                </li>
                                <li class="dev">
                                    <span class="dir">Маркет</span>
                                    <ul>
                                        <li class="down"><a href="/market/" class="a_down">Чудо-Дерево</a></li>
                                        <li class="down"><a href="/market/comissionka" class="a_down">Комисcионка</a></li>
                                        <li class="down"><a href="./" class="a_down">Услуги</a></li>
                                        <li class="down"><a href="./" class="a_down">Дарю</a></li>
                                        <li class="down"><a href="./" class="a_down">Игровая комната</a></li>
                                        <li class="down"><a href="./" class="a_down">Ярмарка</a></li>
                                        <li class="down"><a href="./" class="a_down">Торговый центр</a></li>
                                    </ul>
                                </li>
                                <li class="dev">
                                    <span class="dir">Отдых</span>
                                    <ul>
                                        <li class="down"><a href="#" class="a_down">Городская афиша</a></li>
                                        <li class="down"><a href="#" class="a_down">Детский досуг</a></li>
                                        <li class="down"><a href="#" class="a_down">Весёлая страница</a></li>
                                        <li class="down"><a href="#" class="a_down_italic">• устами младенца</a></li>
                                        <li class="down"><a href="#" class="a_down_italic">• прикольные фото</a></li>
                                        <li class="down"><a href="#" class="a_down_italic">• интересные видео</a></li>
                                        <li class="down"><a href="#" class="a_down">Путеводитель</a></li>
                                        <li class="down"><a href="#" class="a_down">Транспорт справка</a></li>
                                    </ul>
                                </li>
                                <li class="dev">
                                    <span class="dir">Конкурсы</span>
                                    <ul>
                                        <li class="down"><a href="./" class="a_down">Праздник</a></li>
                                        <li class="down"><a href="./" class="a_down">Фильмы</a></li>
                                        <li class="down"><a href="./" class="a_down">Мультфильмы</a></li>
                                    </ul>
                                </li>
                                <li class="dev"><a href="./" class="no_dir">Свое дело</a></li>
                                <li class="dev"><a href="./" class="no_dir">О нас</a></li>
                                -->
                            </ul>
                        </div>

                        <div id="header_menu_right_background">
                        </div>
                    </div>


                    <div id="kroshki">
                        <?php
                        /*
                          <a href="/"><img src="/public/images/home_krohi.png" width="23" height="23" /></a>
                         * if ($this->breadcrumbs != null) { 
                          foreach ($this->breadcrumbs as $b) {
                          echo ' - <a href="'.$b['url'].'">'.$b['title'].'</a>';
                          }
                          } */

                        if ($this->breadcrumbs != null) {
                            echo '<a href="/"><img src="/public/images/home_krohi.png" width="23" height="23" /></a>';
                            foreach ($this->breadcrumbs as $b) {
                                if ($b === end($this->breadcrumbs)) {
                                    // last element
                                    echo ' - <i>' . $b['title'] . '</i>';
                                } else {
                                    echo ' - <a href="' . $b['url'] . '">' . $b['title'] . '</a>';
                                }
                            }
                        }
                        ?>
                    </div>
                </div>


                <div id="body_center_background">
                    <div id="body_center">
                        <?php require_once $view; ?>
                    </div>
                </div>


                <div id="footer_background">
                    <div id="footer_background_right"></div>
                </div>  

                <div id="footer_center">
                    <div id="footer_center_top_block">
                        <div id="footer_line_for_seat_girl">
                            <div id="footer_line_for_seat_girl_left"></div>
                            <div id="footer_line_for_seat_girl_middle"></div>
                            <div id="footer_line_for_seat_girl_right"></div>
                        </div>

                        <div id="footer_girl_on_line"></div>
                    </div>

                    <div id="footer_center_bottom_block">
                        <div id="footer_logo_background">
                            <a href="/"><img src="/public/images/logo_bottom.png" width="148" height="156" alt="Карапузик" /></a>
                        </div>

                        <div id="footer_bottom_block">
                            <div class="nav_footer">
                                <ul class="footer_dropdown">
                                    <li><a href="/catalog/" class="no_dir">Каталог организаций</a></li>
                                    <li><a href="/category/" class="no_dir">Статьи</a></li>
                                    <li class="dev"><a href="/consult/" class="no_dir">Консультации</a></li>
                                    <li class="dev"><a href="/market/" class="no_dir">Маркет</a></li>

                                    <!--
                                    <li>
                                        <span class="footer_dir">• Разделы</span>
                                        <ul>
                                            <li class="footer_down"><a href="/category/" class="a_down">Статьи</a></li>
                                            <li class="footer_down"><a href="/forum/forum.php" class="a_down">Форум</a></li>
                                            <li class="footer_down"><a href="/catalog/" class="a_down">Каталог организаций</a></li>
                                            <li class="footer_down dev"><a href="./" class="a_down">Консультации</a></li>
                                            <li class="footer_down dev"><a href="./" class="a_down">События в городе</a></li>
                                            <li class="footer_down dev"><a href="./" class="a_down">Наши встречи</a></li>
                                            <li class="footer_down dev"><a href="#" class="a_down">Все товары и услуги</a></li>
                                            <li class="footer_down dev"><a href="#" class="a_down">Акции, скидки</a></li>
                                            <li class="footer_down dev"><a href="#" class="a_down">Зелёная Украина</a></li>                                    
                                        </ul>
                                    </li>
                                    <li class="dev">
                                        <span class="footer_dir">• Маркет</span>
                                        <ul>
                                            <li class="footer_down"><a href="/market/" class="a_down">Чудо-Дерево</a></li>
                                            <li class="footer_down"><a href="./" class="a_down">Коммисионка</a></li>
                                            <li class="footer_down"><a href="./" class="a_down">Услуги</a></li>
                                            <li class="footer_down"><a href="./" class="a_down">Дарю</a></li>
                                            <li class="footer_down"><a href="./" class="a_down">Игровая комната</a></li>
                                            <li class="footer_down"><a href="./" class="a_down">Ярмарка</a></li>
                                            <li class="footer_down"><a href="./" class="a_down">Торговый центр</a></li>
                                        </ul>
                                    </li>
                                    <li class="dev">
                                        <span class="footer_dir">• Отдых</span>
                                        <ul>
                                            <li class="footer_down"><a href="#" class="a_down">Городская афиша</a></li>
                                            <li class="footer_down"><a href="#" class="a_down">Детский досуг</a></li>
                                            <li class="footer_down"><a href="#" class="a_down">Весёлая страница</a></li>
                                            <li class="footer_down"><a href="#" class="a_down_italic">• устами младенца</a></li>
                                            <li class="footer_down"><a href="#" class="a_down_italic">• прикольные фото</a></li>
                                            <li class="footer_down"><a href="#" class="a_down_italic">• интересные видео</a></li>
                                            <li class="footer_down"><a href="#" class="a_down">Путеводитель</a></li>
                                            <li class="footer_down"><a href="#" class="a_down">Транспорт справка</a></li>
                                        </ul>
                                    </li>
                                    <li class="dev">
                                        <span class="footer_dir">• Конкурсы</span>
                                        <ul>
                                            <li class="footer_down"><a href="./" class="a_down">Праздник</a></li>
                                            <li class="footer_down"><a href="./" class="a_down">Фильмы</a></li>
                                            <li class="footer_down"><a href="./" class="a_down">Мультфильмы</a></li>
                                        </ul>
                                    </li>
                                    <li class="dev"><a href="#" class="no_dir">• Свое дело</a></li>
                                    <li class="dev"><a href="#" class="no_dir">• О нас</a></li>
                                    -->
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div id="copyright">Karapuz.life © 2014-<?php echo date("Y", time()); ?></div>
                </div>  

                <a style="display:block" href="/auth/contactus"><div id="contact_us"></div></a>
            </div>  

            <div id="fullhd_screen_background">
                <div id="fullhd_screen_center">
                    <div id="fullhd_screen_left_column">
                        <div id="fullhd_screen_left_column_image"></div>  
                    </div>

                    <div id="fullhd_screen_middle_column">
                    </div>

                    <div id="fullhd_screen_right_column">
                        <div id="fullhd_screen_right_column_image"></div>  
                    </div>
                </div>
            </div>


            <div id="scroller"></div>
            <!-- Модули кросбраузерности-->
            <!--[if lt IE 9]>   
            <script src="/public/js/gooddeals/html5shiv/es5-shim.min.js"></script>
            <script src="/public/js/gooddeals/html5shiv/html5shiv.min.js"></script>
            <script src="/public/js/gooddeals/html5shiv/html5shiv-printshiv.min.js"></script>
            <script src="/public/js/gooddeals/respond.min.js"></script><![endif]-->
            <script src="/public/js/jquery.easing.1.3.js"></script>
            <script src="/public/js/bootstrap_alert.js"></script>
            <script src="/public/js/circ.js"></script>
            <script src="/public/js/init.js"></script>
            <script src="/public/js/common.js"></script>
            


            <!-- My JS-->
            <script src="/public/js/gooddeals/jquery/jquery-2.1.4.min.js"></script>
            <script src="/public/js/gooddeals/owl-carousel/owl.carousel.min.js"></script>
            <script src="/public/js/gooddeals/ulslider/jquery.mousewheel.js"></script>
            <script src="/public/js/gooddeals/ulslider/jquery.ulslide.js"></script>
            <!-- Календарь js-->
            <script src="/public/js/gooddeals/calendar/zabuto_calendar.js"></script>
            <!-- init js file-->
            <script src="/public/js/gooddeals/init.js"></script>
            


            <?php
            switch ($this->path['module']) {
                case "IndexController" :
                    // Перед jquery.blueberry.js обязательно должен грузиться jquery-1.11.2.min.js
                    echo '<script src="/public/js/jquery.blueberry.js"></script>'; // центральный слайдер с новостями на главной станице
                    echo '<script src="/public/js/indexinit.js"></script>';
                    break;

                case "ArticleController" :
                    echo '<script src="/public/js/carousel_a.js"></script>';
                    echo '<script src="/public/js/client.js"></script>';
                    break;

                case "CatalogController" :
                    break;

                case "AuthController" :
                    echo '<script src="/public/js/validation.js"></script>';
                    break;
                
                case "GooddealsController" :
                    echo '<script src="/public/js/gooddeals.js"></script>';
                    break;                
            }
            ?>

            <?php ShowAlertIfMessageExists(); ?>

</body>
</html>
