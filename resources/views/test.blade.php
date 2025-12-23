<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <link href="{{ asset('/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/jquery.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

<style>
body, html {
    height: 100%;
    padding: 0;
    margin: 0;
    background-color:#2b2b2b;
    color:#fff;
}

.back-top {
    position: absolute;
    cursor: pointer;
    bottom: -10px;
    left: 50%;
    margin-left: -20px;
    width: 40px;
    height: 40px;
    font-family: 'FontAwesome';
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    text-align: center;
    z-index: 10000;
    font-size: 22px;
    line-height: 40px;
    background: #2b2b2b;
    color: #3498db;
    border:0;
    -webkit-box-shadow: 0 -5px 15px -5px #000;
    -moz-box-shadow: 0 -5px 15px -5px #000;
    box-shadow: 0 -5px 15px -5px #000;
    -webkit-transition: all 300ms linear;
    -moz-transition: all 300ms linear;
    -o-transition: all 300ms linear;
    -ms-transition: all 300ms linear;
    transition: all 300ms linear;
}

.submit {
    background: #957b5f;
    border:2px solid #fff;
    padding: 5px 35px;
    color: #fff;
    cursor: pointer;
    transition: background 100ms linear;
}

.submit:hover {
    background: #ad9173;
    
}

.form-control {
    background: transparent;
    border: 0;
    border-bottom: 2px solid #fff;
    color: #fff !important
}

.form-control input {
    color: #fff !important
}

.form-control:focus {
    outline: none !important;
    background: transparent;
    border-bottom: 2px solid #3498db
}

.color_18 {
    color: #957B5F;
}

.font_5 {
    font: normal normal normal 28px/1.4em lulo-clean-w01-one-bold,sans-serif;
    color: #957B5F;
    font-size: 35px;
    font-weight: bold;
    margin-top: 5px;
}

.alternate{
    background: #242424

}

h6 {
    font-size: 16px;
    line-height: 16px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: bold;
}

.m_bottom_25 {
    margin-bottom: 25px !important;
}

.m_top_25 {
    margin-top: 25px !important;
}

.m_bottom_50 {
    margin-bottom: 50px !important;
}

.parallax {
    /* The image used */
    background-image: url('images/skull-parts-1024x683.jpg');

    /* Full height */
    height: 100%; 

    /* Create the parallax scrolling effect */
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
}

.menu {
    position: fixed; 
    top:0;
    background:#2b2b2b;
    width: 100%;
    z-index: 10;
    padding:15px;
    box-shadow: 0 0 6px #fff;
    opacity: 0.9;
}

.menu ul {
    list-style: none;
    float:right;
    margin: 0
}

.menu ul li{
    display: inline-block;
    text-transform: uppercase;
}

a:hover {
    color: #fff;
    text-decoration: none;
}

.menu ul li a{
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    transition: all 100ms linear;
}

.menu ul li a:hover{ 
    color: #3498db;
}

.sub-text-line {
    border-top: 1px dotted #fff;
    position: relative;
    width: 50px;
    margin: 0 auto;
    text-align: center;
}

@media only screen and (max-device-width: 1024px) {
    .parallax {
        background-attachment: scroll;
    }
}

</style>
</head>
<body>

<div class="parallax"></div>
<nav class="menu">
    <div class="container">
        <div class="row">
            <div class="col-md-3">Berdvaye Inc.</div>
            <div class="col-md-9">
                <ul>
                    <li><a href="#">Home</a> /</li>
                    <li><a href="">Collections</a> /</li>
                    <li><a href="">About</a> /</li>
                    <li><a href="">News</a> /</li>
                    <li><a href="">Authorized Dealers</a></li>
                </ul>
            </div>
            
        </div>
    </div>
</nav>

<section class="section-small-header">
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="text-align:center">
                <div style="height:50px;padding: 25px 20px;">
                    <h4>DISCOVER BOLD AND UNIQUE MODERN ART</h4>
                </div>
            </div>

            <div class="sub-text-line m_bottom_25 m_top_25"></div>
            <div style="padding: 15px;">
                The beauty of the watch movement featured in these modern art creations preserves and magnifies the highest quality vintage watch parts that are meticulously collected from around the world.
                Each work of art is completely unique and one of a kind. Hours of design work, part sorting and polishing go into each piece. Berd Vaye sculptures are made of a clear, shatter-resistant, high-end precious resin that showcase carefully selected mechanical watch components.
                Explore the Berd Vaye master collections and experience the tremendous intricacies and human ingenuity involved in fine watchmaking.
            </div>

        </div>

    </div>
</section>

<section class="section-small-header alternate">
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="text-align:center">
                <div style="height:50px;padding: 25px 20px;">
                    <h4>HOROLOGICAL SCULPTURES</h4>
                </div>
            </div>

            <div class="sub-text-line m_bottom_25 m_top_25"></div>
            <div style="padding: 15px; text-align: center;font-size: 18px;">
                <b>WE BELIEVE</b> IN COMING UP WITH ORIGINAL IDEAS AND TURNING THEM INTO REALITY THAT IS BOTH <b>INNOVATIVE AND MEASURABLE</b>.
            </div>

        </div>
    </div>
</section>

<section class="section-small-header m_bottom_50">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-7" style="text-align:center">
                <div style="padding: 25px 0 0;" >
                    <img src="images/cube-parts.jpg" style="max-width:100%" />
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-lg-4">
                <div style="padding: 18px 0 0;">
                    CUBE
                </div>
                <div class="font_5" style="width: 30px;line-height: 35px">
                    TIME SQUARED
                </div>
                
                <div style="padding: 25px 0 0;" class="m_bottom_25">
                    The <i>Time Squared</i> Cube beautifully enhances the display of watch parts in a structural integrity that seems miraculous.
                </div>
                <a href="" class="submit">View Collection</a>
            </div>
            
            
        </div>
    </div>
</section>

<section class="section-small-header m_bottom_50">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-7" style="text-align:center">
                <div style="padding: 25px 0 0;" >
                    <img src="images/skull-parts-1.jpg" style="max-width:100%" />
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-lg-4">
                <div style="padding: 18px 0 0;">
                    SKULL
                </div>
                <div class="font_5" style="line-height: 35px">
                    LOST IN TIME
                </div>
                
                <div style="padding: 25px 0 0;" class="m_bottom_25">
                    Impeccably crafted, 50 to 100-year-old watch parts collected from around 
                    the world are playfully cast inside the elegantly finished <i>Lost-in-Time Skull</i>.
                </div>

                <a href="" class="submit">View Collection</a>
            </div>
            
            
        </div>
    </div>
</section>

<section class="section-small-header m_bottom_50">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-7" style="text-align:center">
                <div style="padding: 25px 0 0;" >
                    <img src="images/sphere-parts.jpg" style="max-width:100%" />
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-lg-4">
                <div style="padding: 18px 0 0;">
                    SPHERE
                </div>
                <div class="font_5" style="line-height: 35px">
                    360VIEW
                </div>
                
                <div style="padding: 25px 0 0;" class="m_bottom_25">
                    This 360 degree sphere combines Berd Vaye’s meticulously cultivated 
                    watch components in faceted rows and columns in stunningly beautiful allure.
                </div>

                <a href="" class="submit">View Collection</a>
            </div>
            
            
        </div>
    </div>
</section>

<section class="section-small-header m_bottom_25">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-7" style="text-align:center">
                <div style="padding: 25px 0 0;" >
                    <img src="images/cube-parts-2.jpg" style="max-width:100%" />
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-lg-4">
                <div style="padding: 18px 0 0;">
                    PICTURE FRAME
                </div>
                <div class="font_5" style="line-height: 35px">
                    TIME FRAMED
                </div>
                
                <div style="padding: 25px 0 0;" class="m_bottom_25">
                    A more traditional approach to presenting art, 
                    yet still highlighting both the beauty and the magnificence of the 
                    intricate watch movement, <i>Time Framed</i> offers unparalleled elegance 
                    on the wall.
                </div>

                <a href="" class="submit">View Collection</a>
            </div>
            
            
        </div>
    </div>
</section>

<section class="section-small-header alternate">
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="text-align:center">
                <div style="height:50px;padding: 25px 20px;">
                    <h4>CANTACT US</h4>
                </div>
            </div>

            <div class="sub-text-line m_bottom_25 m_top_25"></div>
        </div>

    </div>
</section>

<section class="alternate m_bottom_25" style="position: relative">
    <div class="container">
        <form method="POST">
            <div class="col-md-12">
                <div class="form-group row">
                    <div class="col-6 m_bottom_50">
                        <input class="form-control" placeholder="YOUR NAME: *" type="text" name="firstname" id="firstname-input">
                    </div>
                    <div class="col-6 m_bottom_50">
                        <input class="form-control" placeholder="E-MAIL: *" type="text" name="email" id="email-input">
                    </div>
                    <div class="col-12 m_bottom_50">
                        <textarea class="form-control" placeholder="TELL US EVERYTHING" type="text" name="message" id="message-input"></textarea>
                    </div>
                    <div class="col-12 m_bottom_50" style="text-align: center">
                        <button type="submit" class="submit">SUBMIT</button>
                    </div>
                    
                    <button type="submit" class="back-top">
                        <i class="fa fa-angle-double-up" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            
        </form>
    </div>
    
</section>

<section class="m_bottom_50">
    <div class="col-md-12" style="text-align: center">
        <p style="font-size: 12px">©2017 ALL RIGHT RESERVED. DESIGNED BY BERDVAYE INC. DESIGN</p>
    </div>
</section>

<script>
    $(document).ready(function(){
	
	//Check to see if the window is top if not then display button
	$(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('.back-top').fadeIn();
		} else {
			$('.back-top').fadeOut();
		}
	});
	
	//Click event to scroll to top
	$('.back-top').click(function(){
		$('html, body').animate({scrollTop : 0},1500);
		return false;
	});
	
});
</script>
</body>
</html>
