<html xmlns="http://www.w3.org/1999/html">
    <meta charset="utf-8">
    <meta http-equiv="cache-control" content="no-cache, must-revalidate, post-check=0, pre-check=0">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="pragma" content="no-cache">
    <title>Showcase</title>

    <meta http-equiv="Content-Language" content="en">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <head>
        <script src='web/jquery.min-3.3.1.js'></script>
        <script src="web/styles/bootstrap-3.3.7/js/bootstrap.min.js"></script>
        <script src='vendor/PapaParse-4.5.0/papaparse.min.js'></script>

        <script src='web/js/init.js'></script>
        <link rel="stylesheet" href="web/styles/bootstrap-3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="web/styles/test.css">
    </head>
    <?php
        include 'header.php';
    ?>
    <body onload="init()">
        <div class="container">
            <div id="team-table" class="cool row">
                <table class="table team">
                    <tr class="header">
                        <th scope="col">
                            <span>
                              ID
                            </span>
                        </th>
                        <th scope="col">
                            <span>
                              NAME
                            </span>
                        </th>
                        <th scope="col">
                            <span>
                              CITY
                            </span>
                        </th>
                        <th scope="col">
                            <span>
                              COLOR
                            </span>
                        </th>
                    </tr>
                    <tbody class="cool-body table-striped">
                    </tbody>
                </table>
            </div>
            <div>
                <button type="button" class="btn dark" data-toggle="modal" data-target="#csvModal">
                    <a data-toggle="tooltip" data-placement="top" title="Add Teams Using a CSV file!">+</a>
                    CSV FILE UPLOAD
                </button>
                <button type="button" id="deleteRow" class="btn dark">
                    <a data-toggle="tooltip" data-placement="top" title="Delete Team">+</a>
                    DELETE TEAM
                </button>
                <span class="success-delete">Success!</span>
                <div class="modal fade" id="csvModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Upload Team CSV</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="file" id="csv-file" accept="text/csv">
                            </div>
                            <div class="modal-footer">
                                <span class="success">Success!</span>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                                <button type="button" id='download-csv'class="btn grey">Download Template</button>
                                <button type="button" id='save-csv'class="btn dark">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="fortnite-table">
                <div id="loader"></div>
                <div id="fortnite">
                    <h2 id="userName"></h2>
                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default dark">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" id="life">
                                        Lifetime
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse">
                                <div class="panel-body" id="life-stats"></div>
                            </div>
                        </div>
                        <div class="panel panel-default dark">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" id="duo">
                                        Duos
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse">
                                <div class="panel-body" id="duo-stats"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="content-table"></div>
             <?php
             include 'content.phtml'
             ?>
        </div>
    </body>
</html>