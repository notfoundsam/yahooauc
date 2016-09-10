<!DOCTYPE html>
<html>
  <head>
    <!-- Required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <!-- Color theme for statusbar -->
    <meta name="theme-color" content="#2196f3">
    <!-- Your app title -->
    <title>My App</title>
    <?php echo Asset::css(['framework7.material.min.css', 'framework7.material.colors.min.css', 'my-app.css']); ?>
  </head>
  <body>

    <div class="panel-overlay"></div>
 
    <!-- Left panel -->
    <div class="panel panel-left panel-cover">
      <div class="navbar">
        <div class="navbar-inner">
          <div class="center">Username</div>
        </div>
      </div>

      <div class="list-block">
        <ul>
          <li>
            <a href="aboutd.html" class="item-link item-content close-panel">
              <div class="item-media"><i class="icon icon-f7"></i></div>
              <div class="item-inner">
                <div class="item-title">Item title</div>
              </div>
            </a>
          </li>
          <li>
             <div class="accordion-item">
                <div class="accordion-item-toggle">
                 <a href="#" class="item-link item-content">
                  <div class="item-media"><i class="icon icon-f7"></i></div>
                    <div class="item-inner">
                      <div class="item-title">Won</div>
                      <div class="item-after"><span class="badge">4</span></div>
                    </div>
                  
                  </a>
                </div>
                <div class="accordion-item-content">
                  <div class="list-block" style="padding-left: 16px;">
                    <ul>
                      <li>
                        <a href="aboutd.html" class="item-content close-panel">
                          <div class="item-media"><i class="icon icon-f7"></i></div>
                          <div class="item-inner">
                            <div class="item-title">Pay</div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="aboutd.html" class="item-content close-panel">
                          <div class="item-media"><i class="icon icon-f7"></i></div>
                          <div class="item-inner">
                            <div class="item-title">Paid</div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="aboutd.html" class="item-content close-panel">
                          <div class="item-media"><i class="icon icon-f7"></i></div>
                          <div class="item-inner">
                            <div class="item-title">Received</div>
                          </div>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
            </div>
          </li>
          <li>
            <a href="#" class="item-link item-content">
              <div class="item-media"><i class="icon icon-f7"></i></div>
              <div class="item-inner">
                <div class="item-title">Logout</div>
              </div>
            </a>
          </li>
        </ul>
        <div class="list-block-label">List block label text goes here</div>
      </div>
    </div>
    <!-- Views -->
    <div class="views">
      <!-- Your main view, should have "view-main" class -->
      <div class="view view-main">
        <!-- Pages container, because we use fixed navbar and toolbar, it has additional appropriate classes-->
        <div class="pages navbar-fixed">
          <!-- Page, "data-page" contains page name -->
          <div data-page="index" class="page">
 
            <!-- Top Navbar. In Material theme it should be inside of the page-->
            <div class="navbar">
              <div class="navbar-inner">
                <div class="center">Awesome App</div>
                <div class="right">
                  <a href="#" class="open-panel link icon-only">
                    <i class="icon icon-bars"></i>
                  </a>
                </div>
              </div>
            </div>
 
            <!-- Bottom Toolbar. In Material theme it should be inside of the page-->
            
 
            <!-- Scrollable page content -->
            <div class="page-content">
              <div class="content-block">
              <p>Чтобы далеко не ходить, создадим небольшое приложение-пример, где можно будет посмотреть, как объединить отдельные компоненты фреймворка в единое приложение. Будем использовать slide menu, pull to refresh, infinite scroll, смену material/ios style на лету и огромным списком на 8000 элементов, который не тормозит (virtual list).


Больше ничего не потребуется, другие библиотеки или фреймворки для создания приложения не нужны. Но при желании можно использовать require.js, angular.js, matreshka.js итд.</p>


              <!-- Link to another page -->
              <a href="mobile/aboutd.html">About app</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?= Asset::js(['framework7.min.js', 'my-app.js']); ?>
  </body>
</html> 