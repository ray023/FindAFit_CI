<div class="container">
      <div class="header">
        <nav>
          <ul class="nav nav-pills pull-right">
            <li role="presentation"><a target="_blank" href="https://facebook.com/findafitapp"><i class="fa fa-facebook-official fa-2x"></i></a></li>
            <li role="presentation"><a target="_blank" href="https://twitter.com/FindAFit"><i class="fa fa-twitter fa-2x"></i></a></li>
            <li role="presentation"><a target="_blank" href="https://github.com/ray023/FindAFit"><i class="fa fa-github fa-2x"></i></a></li>
          </ul>
        </nav>
        <h3 class="text-muted">&nbsp;</h3>
      </div>

      <div class="jumbotron">
        <h1>Find A Fit</h1>
        <p class="lead">A free, open source app for the traveling athlete.</p>
        <div class="hero-bottom text-white text-center">
            <p class="label"><a class="btn btn-lg btn-success" href="https://itunes.apple.com/app/apple-store/id940781103?pt=115983802&ct=From%20Website&mt=8"><i class="fa fa-apple fa-2x"></i></a></p>
            <p class="label"><a class="btn btn-lg btn-success" href="http://tinyurl.com/FindAFit-Play"><i class="fa fa-play fa-2x"></i></a></p>
            <p class="label"><a class="btn btn-lg btn-success" href="http://tinyurl.com/FindAFit-WindowsPhone"><i class="fa fa-windows fa-2x"></i></a></p>
            <p class="label"><a class="btn btn-lg btn-success" href="http://tinyurl.com/FindAFit-Amazon"><i class="fa fa-fire fa-2x"></i></a></p>
            <p class="label"><a class="btn btn-lg btn-success" href="http://findafit.info/faf_for_web" target="_blank"><i class="fa fa-globe fa-2x"></i></a></p>
        </div>
      </div>

        <div class="row marketing">
            <div class="col-lg-offset-3 col-lg-6 col-lg-offset-3">
                <small>
                    <em><strong><?php echo $download_count;?></strong> downloads, <strong><?php echo $country_count;?></strong> countries, <strong><?php echo $city_count;?></strong> cities, <strong><?php echo $search_count;?></strong> searches.</em>
                </small>
            </div>
        <div class="col-lg-6">
          <h4>Fast</h4>
          <p>Find closest affiliates in seconds.</p>
        </div>
        <div class="col-lg-6">
          <h4>Free</h4>
          <p>No cost; no ads.</p>
        </div>
        <div class="col-lg-6">
          <h4>Sleek</h4>
            <p>Modern look and feel across iOS and Android devices.</p>
            
            <!--ray start-->
            <div id="faf-screenshots" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                  <li data-target="#faf-screenshots" data-slide-to="0" class="active"></li>
                  <li data-target="#faf-screenshots" data-slide-to="1"></li>
                  <li data-target="#faf-screenshots" data-slide-to="2"></li>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                  <div class="item active">
                    <img src="<?php echo base_url().'images/01_news.jpg';?>" alt="News">
                    <div class="carousel-caption">
                      Stay informed
                    </div>
                  </div>
                  <div class="item">
                    <img src="<?php echo base_url().'images/02_gps.jpg';?>" alt="GPS">
                    <div class="carousel-caption">
                      Nearest boxes by your current location
                    </div>
                  </div>

                  <div class="item">
                    <img src="<?php echo base_url().'images/03_address.jpg';?>" alt="Details">
                    <div class="carousel-caption">
                      Detailed box info
                    </div>
                  </div>
                  <div class="item">
                    <img src="<?php echo base_url().'images/02_gps.jpg';?>" alt="Address">
                    <div class="carousel-caption">
                      Search by an address
                    </div>
                  </div>
                </div>

                <!-- Controls -->
                <a class="left carousel-control" href="#faf-screenshots" role="button" data-slide="prev">
                  <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#faf-screenshots" role="button" data-slide="next">
                  <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
              </div>
            <!-- ray finish-->
            
            
        </div>
          <div class="col-lg-6">
          <h4>What others are saying</h4>
          <blockquote class="bg-info">
              <p>It's the best find a gym app by far. It works as intended quickly.</p>
              <p>As an affiliate, we get apps like this flooding our inboxes. I hate all of them because they want money, ads, and me to update a profile then people to get a profile to do what Find A Fit does 10 times better.</p>
              <p>Congratulations sir. You filled a need in the market.</p>
              <small>Thomas Reesbeck (owner of <a href="http://a2xfit.com/" target="_blank">Wolverine Strength and Conditioning</a>)</small>
          </blockquote>
          <blockquote class="bg-info">
              <p><strong>LOVE.</strong></p>
              <p>Sleek little app with great possibilities! I love to be able to find gyms near me. It will be very useful when traveling.</p>
              <small>Katie Guillot (athlete)</small>
          </blockquote>          
        </div>

      </div>

      <footer class="footer">
          <p><i>This application is not affiliated with or endorsed by CrossFit Inc.</i></p>
        <p>&copy; o1solution 2015</p>
      </footer>

    </div> <!-- /container -->