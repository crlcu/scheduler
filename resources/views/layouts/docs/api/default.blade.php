<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        @include('includes.docs.api.head')
    </head>
    <body>

        <div class="container">
            <div class="row">
                <div class="col-3" id="sidebar">
                    <div class="column-content">
                        <div class="search-header">
                            <img src="/images/logo_128x128.png" class="logo" alt="Logo" />
                            <input id="search" type="text" placeholder="Search">
                        </div>
                        <ul id="navigation">

                            <li><a href="#introduction">Introduction</a></li>

                            

                            <li>
                                <a href="#Tasks">Tasks</a>
                                <ul>
									<li><a href="#Tasks_onoff">onoff</a></li>

									<li><a href="#Tasks_run">run</a></li>
</ul>
                            </li>


                        </ul>
                    </div>
                </div>
                <div class="col-9" id="main-content">

                    <div class="column-content">

                        @include('includes.docs.api.introduction')

                        <hr />

                                                

                                                <a href="#" class="waypoint" name="Tasks"></a>
                        <h2>Tasks</h2>
                        <p></p>

                        
                        <a href="#" class="waypoint" name="Tasks_onoff"></a>
                        <div class="endpoint-header">
                            <ul>
                            <li><h2>PUT</h2></li>
                            <li><h3>onoff</h3></li>
                            <li>api/tasks/{id}/onoff</li>
                          </ul>
                        </div>

                        <div>
                          <p class="endpoint-short-desc">Enable/Disable a task.</p>
                        </div>
                       <!--  <div class="parameter-header">
                             <p class="endpoint-long-desc"></p>
                        </div> -->
                        <form class="api-explorer-form" uri="api/tasks/{id}/onoff" type="PUT">
                          <div class="endpoint-paramenters">
                            <h4>Parameters</h4>
                            <ul>
                              <li class="parameter-header">
                                <div class="parameter-name">PARAMETER</div>
                                <div class="parameter-type">TYPE</div>
                                <div class="parameter-desc">DESCRIPTION</div>
                                <div class="parameter-value">VALUE</div>
                              </li>
                                                           <li>
                                <div class="parameter-name">id</div>
                                <div class="parameter-type">int</div>
                                <div class="parameter-desc">Task id</div>
                                <div class="parameter-value">
                                    <input type="text" class="parameter-value-text" name="id">
                                </div>
                              </li>
                             <li>
                                <div class="parameter-name">Task[is_enabled]</div>
                                <div class="parameter-type">array</div>
                                <div class="parameter-desc">0/1 depending if you want to disable/enable the task</div>
                                <div class="parameter-value">
                                    <input type="text" class="parameter-value-text" name="Task[is_enabled]">
                                </div>
                              </li>

                            </ul>
                          </div>
                           <div class="generate-response" >
                              <!-- <input type="hidden" name="_method" value="PUT"> -->
                              <input type="submit" class="generate-response-btn" value="Generate Example Response">
                          </div>
                        </form>
                        <hr>

                        <a href="#" class="waypoint" name="Tasks_run"></a>
                        <div class="endpoint-header">
                            <ul>
                            <li><h2>GET</h2></li>
                            <li><h3>run</h3></li>
                            <li>api/tasks/{id}/run</li>
                          </ul>
                        </div>

                        <div>
                          <p class="endpoint-short-desc">Run a task.</p>
                        </div>
                       <!--  <div class="parameter-header">
                             <p class="endpoint-long-desc"></p>
                        </div> -->
                        <form class="api-explorer-form" uri="api/tasks/{id}/run" type="GET">
                          <div class="endpoint-paramenters">
                            <h4>Parameters</h4>
                            <ul>
                              <li class="parameter-header">
                                <div class="parameter-name">PARAMETER</div>
                                <div class="parameter-type">TYPE</div>
                                <div class="parameter-desc">DESCRIPTION</div>
                                <div class="parameter-value">VALUE</div>
                              </li>
                                                           <li>
                                <div class="parameter-name">id</div>
                                <div class="parameter-type">int</div>
                                <div class="parameter-desc">Task id</div>
                                <div class="parameter-value">
                                    <input type="text" class="parameter-value-text" name="id">
                                </div>
                              </li>

                            </ul>
                          </div>
                           <div class="generate-response" >
                              <!-- <input type="hidden" name="_method" value="GET"> -->
                              <input type="submit" class="generate-response-btn" value="Generate Example Response">
                          </div>
                        </form>
                        <hr>


                    </div>
                </div>
            </div>
        </div>


    </body>
</html>
