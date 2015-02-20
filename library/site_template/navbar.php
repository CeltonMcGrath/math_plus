<!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="http://mathplus.math.utoronto.ca">Math+</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="splash.php">Home</a></li>
            <!-- Account settings -->
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Account<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
              	<li><a href="students.php">Manage student information and programs</a></li>
                <li><a href="guardians.php">Manage guardian information</a></li>
                <li><a href="edit_account.php">Manage account settings</a></li>                
              </ul>
            </li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="faq.php">FAQ</a></li>
            <!-- Important information -->
             <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Important info<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="information.php?view=student_code_of_conduct">Student Code of Conduct</a></li>
                <li><a href="information.php?view=program_policies">Program Policies</a></li>
                <li><a href="information.php?view=user_terms_and_conditions">Terms and Conditions</a></li>
                <li><a href="information.php?view=privacy_statement">Privacy statement</a></li>
              </ul>
            </li>
            <li><a href="information.php?view=user_guide">Help</a></li>           
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>