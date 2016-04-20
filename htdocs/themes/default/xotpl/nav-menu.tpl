<nav class="navbar navbar-default navbar-static-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="{xoAppUrl}" title="{$xoops_sitename}"><img src="{xoImgUrl 'themes/default/assets/img/logo.png'}"></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="#">Link <span class="sr-only">(current)</span></a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Action</a></li>
            <li><a href="#"><span class="glyphicon glyphicon-none" aria-hidden="true"></span> Another action</a></li>
            <li class="disabled"><a href="#"><span class="glyphicon glyphicon-none" aria-hidden="true"></span>Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#"><span class="glyphicon glyphicon-link" aria-hidden="true"></span>Separated link</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#"><span class="glyphicon glyphicon-none" aria-hidden="true"></span>One more separated link</a></li>
          </ul>
        </li>
      </ul>
      {if $search_url|default:false}
      <form class="navbar-form navbar-right" role="search" action="{$search_url}" method="get">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Search" name="query">
          <span class="input-group-btn"><button type="submit" class="btn btn-default" aria-label="Search"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button></span>
        </div>
      </form>
      {/if}
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
