<?php
/*
Template Name: akvoNetwork
*/
?>
<?php get_header(); ?>
  <!--Start of Akvo.org Network page-->
  <div id="content" class="floats-in networkPage">
    <h1 class="backLined">See it happen</h1>
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
      <div class="fullWidthParag wrapper">
        <?php the_content(); ?>
      </div>
    <?php endwhile; // end of the loop. ?>

    <section id="akvoDashboard">
      <h2>Data collected with Akvo tools</h2>

      <ul class="wrapper">

        <?php
        use DataFeed\DataFeed;

        function feedData($label, $apiURL, $timeOut=3600, $pagination=null) {
          $feedHandle = DataFeed::handle($label, $apiURL, $timeOut, $pagination);
          return $feedHandle->getCurrentItem();
        }
        $refreshSeconds = 3600; //an hour
        // RSR KPIs fetch
        $rightNowInAkvo = feedData('rightNowInAkvo', 'http://rsr.akvo.org/api/v1/right_now_in_akvo/', $refreshSeconds);
        $rsrUpdateCount = feedData('rsrUpdateCount', 'http://rsr.akvo.org/api/v1/project_update/?limit=1', $refreshSeconds);
        // OpenAid KPIs fetch
        $openAidActivities = feedData(
          'openAidActivities',
          'http://oipa.openaidsearch.org/api/v2/activities/?format=json&limit=1', $refreshSeconds
        );
        $openAidOrgs = feedData(
          'openAidOrgs',
          'http://oipa.openaidsearch.org/api/v2/organisations/?format=json&limit=1', $refreshSeconds
        );
        // Akvopedia KPIs fetch
        $akvopediaAnalytics = feedData(
          'akvopediaAnalytics',
          'http://analytics.akvo.org/index.php?module=API&method=API.get&idSite=9&period=range&date=2013-04-01,today&format=json',
          $refreshSeconds
        );
        $akvopediaArticles = feedData(
          'akvopediaArticles',
          'http://akvopedia.org/wiki/api.php?action=query&meta=siteinfo&siprop=statistics&format=json',
          $refreshSeconds
        );
        ?>

        <li class="dashSingle" id="rsrDash">
          <h2>Akvo RSR</h2>
          <ul class="rsrData dashData">
            <li>
              <h4>Projects:</h4>
              <span ><?= $rightNowInAkvo['number_of_projects']?></span>
            </li>
            <li>
              <h4>Number of updates:</h4>
              <span ><?= $rsrUpdateCount['meta']['total_count']?></span>
            </li>
            <li>
              <h4>Organisations Using RSR:</h4>
              <span id=""><?= $rightNowInAkvo['number_of_organisations']?></span>
            </li>
            <li>
              <h4>Project Budgets:</h4>
              <span id="">
                € <?= round($rightNowInAkvo['projects_budget_millions'])/1000 ?>
                <span class="unit">billion</span>
              </span>
            </li>
          </ul>
          <a href="#"
             title="<p><em style='display:block;color:rgb(114, 205, 255);'>How is this data collected?</em> Automatically from the Akvo RSR database via the <a href='https://github.com/akvo/akvo-rsr/wili/Akvo-RSR-API'>RSR API</a></p>
          <p><em style='display:block;color:rgb(114, 205, 255);'>How often is this data refreshed?</em> Every four hours.</p>"
             class="tooltips moreLink ">info
          </a>
          <a href="" class="moreLink darkBg  hidden">See more</a>
        </li>

        <li class="dashSingle" id="flowDash">
          <h2>Akvo Flow</h2>
          <ul class="flowData dashData">
            <li>
              <h4>Surveys Created:</h4>
                <span>
                <?php the_field('flow_surveys'); ?>
              </span></li>
            <li>
              <h4>Data Points:</h4>
                <span>
                <?php the_field('flow_data_points'); ?>
              </span></li>
            <li>
              <h4>Devices:</h4>
                <span>
                <?php the_field('flow_devices'); ?>
              </span></li>
            <li><!--<h4>People Helped:</h4><span>2,013,237</span>-->
              <h4>Organisations using FLOW:</h4>
              <span>
              <?php the_field('organisations_using_flow'); ?>
              </span>
            </li>
          </ul>
          <a href="#"
             title="<p><em style='display:block;color:rgb(114, 205, 255);'>How is this data collected?</em> Manually, via a script run on the Google App Enging FLOW instances.</p>
            <p><em style='display:block;color:rgb(114, 205, 255);'>How often is this data refreshed?</em> Monthly.</p>"
             class="tooltips moreLink">info</a>
          <a href="" class="moreLink darkBg  hidden">See more</a>
        </li>

        <li class="dashSingle" id="opendaidDash">
          <h2>Akvo Openaid</h2>
          <ul class="openAidData dashData">
            <li>
              <h4>Total activities:</h4>
              <span id=""><?= $openAidActivities['meta']['total_count']?></span>
            </li>
            <li>
              <h4>Total organisations:</h4>
              <span id=""><?= $openAidOrgs['meta']['total_count']?></span>
            </li>
            <li>
              <h4>Total commitments:</h4>
                <span id="">
                  <?php the_field('openaid_commit'); ?><span class="unit">billion</span>
                </span>
            </li>
            <li>
              <h4>More Stats soon</h4>
            </li>
          </ul>
          <a href="#"
             title="<p><em style='display:block;color:rgb(114, 205, 255);'>How is this data collected?</em> 'Total commitments' is collected manually,
              the other values are collected via the <a href='https://github.com/openaid-IATI/'>OpenAid API</a></p>
              <p><em style='display:block;color:rgb(114, 205, 255);'>How often is this data refreshed?</em> 'Total commitments' is updated monthly,
              the rest is refreshed every four hours.</p>"
             class="tooltips moreLink">info</a>
          <a href="" class="moreLink darkBg hidden">See more</a>
        </li>

        <li class="dashSingle" id="akvopediaDash">
          <h2>Akvopedia</h2>
          <ul class="wikiData dashData">
            <li>
              <h4>Articles:</h4>
              <span id=""><?= $akvopediaArticles['query']['statistics']['articles'] ?></span>
            </li>
            <li>
              <h4>Page Views:</h4>
              <span id="">
                <?= round((2792519 + $akvopediaAnalytics['nb_pageviews'])/1000)/1000 ?>
                <span class="unit">million</span>
              </span>
            </li>
            <li>
              <h4>Visits:</h4>
              <span id="number_of_visits"><?= $akvopediaAnalytics['nb_visits'] + 737347 ?></span>
            </li>
            <li>
              <h4>More Stats soon</h4>
            </li>
          </ul>
          <?php do_shortcode('[jsondata_feed slug="akvopedia-analytics" module="API" method="API.get" idSite="9" period="range" date="2013-04-01,today" format="json" token_auth="1d1b520b11bea9a3b525b99531ec171a"]'); ?>
          <a href="#"
             title="<p><em style='display:block;color:rgb(114, 205, 255);'>How is this data collected?</em> 'Articles' is collected automatically using the Mediawiki API.
                  The rest is collected automatically from the Piwik API</p>
                  <p><em style='display:block;color:rgb(114, 205, 255);'>How often is this data refreshed?</em> Every four hours.</p>"
             class="tooltips moreLink">info</a>
          <a href="" class="moreLink darkBg hidden">See more</a>

        </li>
      </ul>
    </section>



    <section id="rsrProjectUpdates">
      <h2>RSR: Latest project updates</h2>
      <nav class="anchorNav2 wrapper">
        <ul class>
          <li><a href="/seeithappen/all-rsr-project-updates/">Browse all latest project updates</a> </li>
          <li  class="rss"><a href="http://rsr.akvo.org/rss/all-updates" rel="alternate" type="application/rss+xml">RSS link for all RSR updates</a></li>
        </ul>
      </nav>
      <ul id="updatesWrapperJS" class="floats-in wrapper">
        <?php
          // RSR updates fetch
          $rsrUpdates = feedData(
            'rsrUpdates',
            'http://rsr.akvo.org/api/v1/project_update_extra/?limit=60&photo__gte=a',
            $refreshSeconds,
            "page-url=next:meta.next"
          );
          $updates = $rsrUpdates['objects'];
          $rsr_domain = "http://rsr.akvo.org";
          $count = 1;
          foreach($updates as $update) {
            if ($update['photo'] != '') {
//              if ($count > 3)
//                break;
//              $count++;
              $date = explode('T', $update['time_last_updated']);
              $date = $date[0];
              $full_name = $update['user']['first_name'] . " " . $update['user']['last_name'];
              $country_and_city = $update['project']['primary_location']['country']['name'];
              if ($update['project']['primary_location']['city'])
                $country_and_city = $update['project']['primary_location']['city'] .", ". $country_and_city;
              json_data_render_update(
                $rsr_domain , $update['absolute_url'], $update['title'], $update['photo'], $date, $full_name,
                $update['user']['organisation']['name'], $update['user']['organisation']['absolute_url'],
                $country_and_city, $update['text']
              );
            }
          }
        ?>
      </ul>
    </section>

  </div>
  <!-- end content -->
<?php get_footer(); ?>