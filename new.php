<?php
include "include/init.php";

?>



<div id="slider">
    <a href="javascript:void(0);" class="slider-next"><i class="iconZ-angle-left"></i></a>
    <a href="javascript:void(0);" class="slider-prev"><i class="iconZ-angle-right"></i></a>
    <div id="sliderOwl" class="owl-carousel owl-theme">

        <div><a href="works/arcade-fire1"><img src="/upload/images/topic/default.png"/></a></div>
        <div><a href="works/arcade-fire2"><img src="/upload/images/topic/default.png"/></a></div>
        <div><a href="works/arcade-fire3"><img src="/upload/images/topic/default.png"/></a></div>
        <div><a href="works/arcade-fire4"><img src="/upload/images/topic/default.png"/></a></div>
        <div><a href="works/arcade-fire5"><img src="/upload/images/topic/default.png"/></a></div>
        <div><a href="works/arcade-fire6"><img src="/upload/images/topic/default.png"/></a></div>
        <div><a href="works/arcade-fire7"><img src="/upload/images/topic/default.png"/></a></div>
        <div><a href="works/arcade-fire8"><img src="/upload/images/topic/default.png"/></a></div>
    </div>

</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-10 itemBox">
            <div class="row">
                <div class="col-xs-11">

                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-2">
            <div class="row">
                <div class="col-xs-12 itemBox">
                    <form action="">
                        <h4><i class="iconZ-search"></i> Search</h4>

                        <div class="input-group">
                            <select class="form-control" name="categories" id="categories">
                                <option value="0">All</option>
                                <option value="test">test</option>
                            </select>
                            <span class="input-group-addon"><i class="iconZ-th"></i></span>
                        </div>

                        <div class="input-group">
                            <input type="text" name="search" id="search" class="form-control" placeholder="Search..">
                            <span class="input-group-btn">
                                <button class="btn btn-primary"><i class="iconZ-search"></i></button>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="col-xs-12 itemBox">
                    <h4><i class="iconZ-th"></i> Categories</h4>
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                        <div class="panel panel-default">
                            <div class="panel-heading" id="headingOne" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                <h4 class="panel-title">
                                        Collapsible Group Item #1
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel"
                                 aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <ul class="list-group">
                                        <li class="list-group-item">Cras justo odio</li>
                                        <li class="list-group-item">Dapibus ac facilisis in</li>
                                        <li class="list-group-item">Morbi leo risus</li>
                                        <li class="list-group-item">Porta ac consectetur ac</li>
                                        <li class="list-group-item">Vestibulum at eros</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

