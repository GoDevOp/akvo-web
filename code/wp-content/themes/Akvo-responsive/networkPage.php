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
      <li class="dashSingle" id="rsrDash">
        <h2>Akvo RSR</h2>
        <?php do_shortcode('[jsondata_feed slug="right-now-in-akvo"]'); ?>
        <a href="#"
         title="<p><em style='display:block;color:rgb(114, 205, 255);'>How is this data collected?</em> Automatically from the Akvo RSR database via the <a href='https://github.com/akvo/akvo-rsr/wili/Akvo-RSR-API'>RSR API</a></p>
            <p><em style='display:block;color:rgb(114, 205, 255);'>How often is this data refreshed?</em> Every four hours.</p>"
         class="tooltips moreLink ">info</a> <a href="" class="moreLink darkBg  hidden">See more</a> </li>
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
            <h4>Organisations using FLOW</h4>
            <span>
            <?php the_field('organisations_using_flow'); ?>
            </span> </li>
        </ul>
        <a href="#"
         title="<p><em style='display:block;color:rgb(114, 205, 255);'>How is this data collected?</em> Manually, via a script run on the Google App Enging FLOW instances.</p>
            <p><em style='display:block;color:rgb(114, 205, 255);'>How often is this data refreshed?</em> Monthly.</p>"
         class="tooltips moreLink">info</a> <a href="" class="moreLink darkBg  hidden">See more</a>
      </li>

      <li class="dashSingle" id="kpiChart"></li>
      <script type="text/javascript" src="https://www.google.com/jsapi"></script>

      <script type="text/javascript">
        google.load('visualization', '1', {'packages' : ['corechart']});
        google.setOnLoadCallback(function() { drawChart() });

//        var dataSourceUrl = 'https://spreadsheets.google.com/tq?key=1G9wXq8cSKSExacDlizoWSoW1PDIEVkki7lMi9M7Q6bc&pub=1';
//        var dataSourceUrl = 'https://spreadsheets.google.com/tq?key=1DliXl5driOAoHDhRgPOQKigACEI4-pdF42mZsHm_EmA&pub=1&range=A1:A13,F1:F13,F14:F26';


        function drawChart() {
          var charts = [
            {
              'name': 'kpiChart',
              'dataSourceUrl': 'https://spreadsheets.google.com/tq?key=1DliXl5driOAoHDhRgPOQKigACEI4-pdF42mZsHm_EmA&pub=1&range=A1:A13,F1:F13,F14:F26',
              'visualization': google.visualization.LineChart,
              'options': kpiOptions
            },
            {
              'name': 'flowChart',
              'dataSourceUrl': 'https://spreadsheets.google.com/tq?key=1G9wXq8cSKSExacDlizoWSoW1PDIEVkki7lMi9M7Q6bc&pub=1',
              'visualization': google.visualization.BarChart,
              'options': flowOptions
            },
            {
              'name': 'contractChart',
              'dataSourceUrl': 'https://spreadsheets.google.com/tq?key=1DliXl5driOAoHDhRgPOQKigACEI4-pdF42mZsHm_EmA&pub=1&range=A1:A13,C1:C13,C14:C26',
              'visualization': google.visualization.LineChart,
              'options': contractOptions
            },
            {
              'name': 'resultsChart',
              'dataSourceUrl': 'https://spreadsheets.google.com/tq?key=1xhEs8bn9_QqushURNcdrpWjLwIOATnBQLPxkEAk7g5s&pub=1&range=B1:B16,E1:E16,C1:C16',
              'visualization': google.visualization.LineChart,
              'options': resultsOptions
            }
          ];

          for (var i=0; i<charts.length; i++) {
            var query = new google.visualization.Query(charts[i].dataSourceUrl);
            function wrapper(i) {
              return function(dataFromServer) {
                handleQueryResponse(dataFromServer, i);
              }
            }
            query.send(wrapper(i));
          }

          function handleQueryResponse(response, count) {
            var chart = charts[count]
            var data = response.getDataTable();
            var rendering = new chart.visualization(document.getElementById(chart.name));
            var options = chart.options(data);
            rendering.draw(data, options);
          }

          function baseOptions() {
            var font = $(".dashData.flowData li").css("font-family");
            var baseAxis = {
              gridlines: {color: '#3d352e'}, // grid lines color
              textStyle: { //labels styling
                color: '#574F48',
                fontName: font
              }
            }
            return {
              titlePosition: 'in', // chart title within the chart area
              titleTextStyle: {
                color: '#57c1e8',
                fontName: font,
                fontSize: 12,
                bold: false,
                italic: false
              },
              backgroundColor: '#19191c', // color of the whole div, TODO: couldn't get this from css
              chartArea: {
                backgroundColor: '#231d1a', // color of the actual chart, TODO: couldn't get this from css
                left:'15%', top: '10%',
                width:'70%', height:'72%'
              },
              hAxis: baseAxis,
              vAxis: baseAxis,
              height: 350
            }
          }

          function kpiOptions(data) {
            var font = $(".dashData.flowData li").css("font-family");
            var options =  {
              title: 'EXPECTED PIPELINE VALUE ACHIEVED',
              curveType: 'function',
              colors:['#57c1e8', '#bf83b9'], // color of the actual bars
              legend: {
                position: 'top',
                textStyle: {
                  color: '#574F48',
                  fontName: font
                }
              },
              vAxis:{
                ticks: [
                  {v:0.5, f:'50%'},
                  {v:0.75, f:'75%'},
                  {v:1.0, f:'100%'},
                  {v:1.25, f:'125%'},
                  {v:1.5, f:'150%'},
                  {v:1.75, f:'175%'}
                ]
              }
            };
            return $.extend(true, {}, baseOptions(), options);
          }

          function flowOptions(data) {
            // data: the google spreadsheet data "JSON"
            // get (incomplete) info from the style sheets
            var barColor = $(".dashData.flowData li").css("color");
            var font = $(".dashData.flowData li").css("font-family");

            // create x-axis labels from first column in the spreadsheet
            var arrayLength = data.tf.length;
            var tics = [];
            for (i=0; i<arrayLength; i++  ) {
              tics[i] = data.tf[i].c[0].v;
            }
            var options = {
              title: "AKVO FLOW SURVEY COUNT",
              titleTextStyle: {
                color: '#e77c00',
              },
              orientation: 'horizontal', // vertical bars
              colors:[barColor], // color of the actual bars
              hAxis: {
                ticks: tics // x-axis labels
              },
              legend: {position: 'none'} // remove bar legend
            };
            return $.extend(true, {}, baseOptions(), options);
          }

          function contractOptions(data) {
            var font = $(".dashData.flowData li").css("font-family");
            var options =  {
              title: 'SIGNED CONTRACTS VALUE',
//              curveType: 'function',
              colors:['#57c1e8', '#bf83b9'], // color of the lines
              legend: {
                position: 'top',
                textStyle: {
                  color: '#574F48',
                  fontName: font
                }
              },
              vAxis:{
                ticks: [
                  {v:1000000, f:'€1 000 000'},
                  {v:2000000, f:'€2 000 000'},
                  {v:3000000, f:'€3 000 000'},
                  {v:4000000, f:'€4 000 000'}
                ]
              }
            };
            return $.extend(true, {}, baseOptions(), options);
          }

          function resultsOptions(data) {
            var font = $(".dashData.flowData li").css("font-family");
            var options =  {
              title: 'RESULTS',
//              curveType: 'function',
              colors:['#57c1e8', '#bf83b9'], // color of the lines
              legend: {
                position: 'top',
                textStyle: {
                  color: '#574F48',
                  fontName: font
                }
              },
              vAxes:{
                0 : {
                  ticks: [
                    {v:-3.0, f:'-300%'},
                    {v:-1.5, f:'-150%'},
                    {v:0, f:'0%'},
                    {v:1.5, f:'150%'},
                    {v:3.0, f:'300%'},
                  ]
                },
                1 : {
                  ticks: [
                    {v:-150000, f:'-€150 000'},
                    {v:0, f:'€0'},
                    {v:150000, f:'€150 000'},
                    {v:300000, f:'€300 000'},
                    {v:450000, f:'€450 000'},
                  ]
                }
              },
              series: {
                0: {targetAxisIndex:0},
                1: {targetAxisIndex:1}
              }
            };
            return $.extend(true, {}, baseOptions(), options);
          }
        }

      </script>

      <li class="dashSingle" id="flowChart"></li>

      <li class="dashSingle" id="contractChart"></li>

      <li class="dashSingle" id="resultsChart"></li>


      <li class="dashSingle" id="opendaidDash">
        <h2>Akvo Openaid</h2>
        <ul class="openAidData dashData">
          <li>
            <h4>Total activities:</h4>
            <span id="">
            <?php do_shortcode('[jsondata_feed slug="openaid-activities" format="json" limit="1"]'); ?>
            </span> </li>
          <li>
            <h4>Total organisations:</h4>
            <span id="">
            <?php do_shortcode('[jsondata_feed slug="openaid-orgs" format="json" limit="1"]'); ?>
            </span> </li>
          <li>
            <h4>Total commitments:</h4>
            <span id="">
            <?php the_field('openaid_commit'); ?>
            <span class="unit">billion</span> </span> </li>
          <li>
            <h4>More Stats soon</h4>
          </li>
        </ul>
        <a href="#"
         title="<p><em style='display:block;color:rgb(114, 205, 255);'>How is this data collected?</em> 'Total commitments' is collected manually,
            the other values are collected via the <a href='https://github.com/openaid-IATI/'>OpenAid API</a></p>
            <p><em style='display:block;color:rgb(114, 205, 255);'>How often is this data refreshed?</em> 'Total commitments' is updated monthly,
            the rest is refreshed every four hours.</p>"
         class="tooltips moreLink">info</a> <a href="" class="moreLink darkBg hidden">See more</a> </li>
      <li class="dashSingle" id="akvopediaDash">
        <h2>Akvopedia</h2>
        <?php do_shortcode('[jsondata_feed slug="akvopedia-analytics" module="API" method="API.get" idSite="9" period="range" date="2013-04-01,today" format="json" token_auth="1d1b520b11bea9a3b525b99531ec171a"]'); ?>
        <a href="#"
         title="<p><em style='display:block;color:rgb(114, 205, 255);'>How is this data collected?</em> 'Articles' is collected automatically using the Mediawiki API.
            The rest is collected automatically from the Piwik API</p>
            <p><em style='display:block;color:rgb(114, 205, 255);'>How often is this data refreshed?</em> Every four hours.</p>"
         class="tooltips moreLink">info</a> <a href="" class="moreLink darkBg hidden">See more</a> </li>
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
      <?php do_shortcode('[jsondata_feed slug="rsr-updates" limit="3" photo__gte="a"]'); ?>
    </ul>
  </section>
  <section id="rsrNetworkMap">
    <h2>Akvo RSR map of all projects</h2>
    <div class="wrapper">
      <div class= "akvo_map centerED" id="akvo_map" style="width:975px;height:600px;"></div>
    </div>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript">
    var googleMap = {
      canvas: document.getElementById('akvo_map'),
      options: {
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        streetViewControl: false
      },
      projects: <?php do_shortcode('[jsondata_feed slug="rsr-projects-global-map" status__in="N,H,A,C" limit="1000" offset="0"]'); ?>,
      projects2: <?php do_shortcode('[jsondata_feed slug="rsr-projects-global-map" status__in="N,H,A,C" limit="1000" offset="1000"]'); ?>,

      load: function() {
        var map = new google.maps.Map(this.canvas, this.options);
        var bounds = new google.maps.LatLngBounds();
        var i;
        var all_projects = this.projects.concat(this.projects2);

        for (i = 0; i < all_projects.length; i++) {
          var project = all_projects[i];
          var position = new google.maps.LatLng(project.latitude, project.longitude);

          var marker = new google.maps.Marker({
            icon: '<?php bloginfo('template_directory'); ?>/images/blueMarker.png',
            position: position,
            map: map
          });


          window['contentString'+i] = '<div class="mapInfoWindow" style="height:150px; min-height:150px; max-height:150px; overflow:hidden;">'+
            '<a href="' + project.absolute_url + '">' + project.title +'</a>'+
            '<div style="text-align: center; margin-top: 10px;">'+
            '<a href="' + project.absolute_url + '" title="' + project.title +'">'+
            '<img src="' + project.map_thumb + '" alt="">'+
            '</a>'+
            '</div>'+
            '</div>';



          (function(marker, i) {
            google.maps.event.addListener(marker, 'click', function() {
              infowindow = new google.maps.InfoWindow({
                content: window['contentString'+i]
              });
              infowindow.open(map, marker);
            });
          })(marker, i);


          bounds.extend(marker.position);
        }

        map.fitBounds(bounds);
        map.panToBounds(bounds);

        var listener = google.maps.event.addListener(map, "idle", function() {
          if (map.getZoom() > 8) map.setZoom(8);
          google.maps.event.removeListener(listener);
        });

      }
    };
    window.onload = function (){googleMap.load()};
  </script>
  </section>
</div>
<!-- end content -->
<?php get_footer(); ?>
