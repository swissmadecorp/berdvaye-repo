@extends('layouts.default')

@section ("title")
  Authorized Dealers
@endsection

@section ('content')
<div class="pageLayout">
  <!-- HEADER -->
  <header class="bg-black">
    <div class="container-fluid clearfix">
      <button class="menuToggler"> </button>
        @livewire('header')
        <div id="cart-component">
          @livewire('cart-component')
        </div>
    </div>
  </header>

  <!-- MAIN -->
  <main class="bg-darkgray">
    <div class="defaultMap">
      <div class="defaultMap-wrap">
        <div id="info_div"></div>
        <div class="row" style="display: none">
          <div class="col-12 col-sm-12 col-md-4" id="selectContainer" style="visibility: hidden">
            <div class="selectControl">
              <select id="locationSelect" class="form-control"></select>
            </div>
          </div>
        </div>

        <div id="map" style="width: 100%; height: 100%"></div>
        <div class="defaultMap-ctrl" style="display:none">
          <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
              <div class="seachControl">
                <input id="pac-input" class="form-control" type="text" placeholder="Search by zipcode" />
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-9">
              <div class="row">
                <div class="col-12 col-sm-12 col-md-1 col-lg-2"> <span class="pt-2 d-block">OR</span></div>
                <div class="col-12 col-sm-12 col-md-4">
                  <div class="selectControl">
                    <select class="form-control">
                      <option>All States</option>
                      <option value="AL">Alabama</option>
                      <option value="AK">Alaska</option>
                      <option value="AZ">Arizona</option>
                      <option value="AR">Arkansas</option>
                      <option value="CA">California</option>
                      <option value="CO">Colorado</option>
                      <option value="CT">Connecticut</option>
                      <option value="DE">Delaware</option>
                      <option value="DC">District Of Columbia</option>
                      <option value="FL">Florida</option>
                      <option value="GA">Georgia</option>
                      <option value="HI">Hawaii</option>
                      <option value="ID">Idaho</option>
                      <option value="IL">Illinois</option>
                      <option value="IN">Indiana</option>
                      <option value="IA">Iowa</option>
                      <option value="KS">Kansas</option>
                      <option value="KY">Kentucky</option>
                      <option value="LA">Louisiana</option>
                      <option value="ME">Maine</option>
                      <option value="MD">Maryland</option>
                      <option value="MA">Massachusetts</option>
                      <option value="MI">Michigan</option>
                      <option value="MN">Minnesota</option>
                      <option value="MS">Mississippi</option>
                      <option value="MO">Missouri</option>
                      <option value="MT">Montana</option>
                      <option value="NE">Nebraska</option>
                      <option value="NV">Nevada</option>
                      <option value="NH">New Hampshire</option>
                      <option value="NJ">New Jersey</option>
                      <option value="NM">New Mexico</option>
                      <option value="NY">New York</option>
                      <option value="NC">North Carolina</option>
                      <option value="ND">North Dakota</option>
                      <option value="OH">Ohio</option>
                      <option value="OK">Oklahoma</option>
                      <option value="OR">Oregon</option>
                      <option value="PA">Pennsylvania</option>
                      <option value="RI">Rhode Island</option>
                      <option value="SC">South Carolina</option>
                      <option value="SD">South Dakota</option>
                      <option value="TN">Tennessee</option>
                      <option value="TX">Texas</option>
                      <option value="UT">Utah</option>
                      <option value="VT">Vermont</option>
                      <option value="VA">Virginia</option>
                      <option value="WA">Washington</option>
                      <option value="WV">West Virginia</option>
                      <option value="WI">Wisconsin</option>
                      <option value="WY">Wyoming</option>
                    </select>
                  </div>
                </div>
                <div class="col-12 col-sm-12 col-md-4">
                  <div class="selectControl">
                    <select class="form-control">
                      <option>All Cities</option>
                      <option value="Adams">Adams</option>
                      <option value="Adams Center">Adams Center</option>
                      <option value="Addison">Addison</option>
                      <option value="Airmont">Airmont</option>
                      <option value="Akron">Akron</option>
                      <option value="Albany">Albany</option>
                      <option value="Albertson">Albertson</option>
                      <option value="Albion">Albion</option>
                      <option value="Alden">Alden</option>
                      <option value="Alexandria Bay">Alexandria Bay</option>
                      <option value="Alfred">Alfred</option>
                      <option value="Allegany">Allegany</option>
                      <option value="Altamont">Altamont</option>
                      <option value="Amagansett">Amagansett</option>
                      <option value="Amherst">Amherst</option>
                      <option value="Amityville">Amityville</option>
                      <option value="Amsterdam">Amsterdam</option>
                      <option value="Andover">Andover</option>
                      <option value="Angola">Angola</option>
                      <option value="Angola on the Lake">Angola on the Lake</option>
                      <option value="Apalachin">Apalachin</option>
                      <option value="Aquebogue">Aquebogue</option>
                      <option value="Arcade">Arcade</option>
                      <option value="Ardsley">Ardsley</option>
                      <option value="Arlington">Arlington</option>
                      <option value="Armonk">Armonk</option>
                      <option value="Athens">Athens</option>
                      <option value="Atlantic Beach">Atlantic Beach</option>
                      <option value="Attica">Attica</option>
                      <option value="Auburn">Auburn</option>
                      <option value="Averill Park">Averill Park</option>
                      <option value="Avon">Avon</option>
                      <option value="Babylon">Babylon</option>
                      <option value="Bainbridge">Bainbridge</option>
                      <option value="Baiting Hollow">Baiting Hollow</option>
                      <option value="Baldwin">Baldwin</option>
                      <option value="Baldwin Harbor">Baldwin Harbor</option>
                      <option value="Baldwinsville">Baldwinsville</option>
                      <option value="Ballston Spa">Ballston Spa</option>
                      <option value="Balmville">Balmville</option>
                      <option value="Bardonia">Bardonia</option>
                      <option value="Barnum Island">Barnum Island</option>
                      <option value="Batavia">Batavia</option>
                      <option value="Bath">Bath</option>
                      <option value="Bay Park">Bay Park</option>
                      <option value="Bay Shore">Bay Shore</option>
                      <option value="Bay Wood">Bay Wood</option>
                      <option value="Bayport">Bayport</option>
                      <option value="Bayville">Bayville</option>
                      <option value="Beacon">Beacon</option>
                      <option value="Beaverdam Lake-Salisbury Mills">Beaverdam Lake-Salisbury Mills</option>
                      <option value="Bedford">Bedford</option>
                      <option value="Bedford Hills">Bedford Hills</option>
                      <option value="Bellerose">Bellerose</option>
                      <option value="Bellerose Terrace">Bellerose Terrace</option>
                      <option value="Bellmore">Bellmore</option>
                      <option value="Bellport">Bellport</option>
                      <option value="Belmont">Belmont</option>
                      <option value="Bensonhurst">Bensonhurst</option>
                      <option value="Bergen">Bergen</option>
                      <option value="Bethpage">Bethpage</option>
                      <option value="Big Flats">Big Flats</option>
                      <option value="Billington Heights">Billington Heights</option>
                      <option value="Binghamton">Binghamton</option>
                      <option value="Black River">Black River</option>
                      <option value="Blasdell">Blasdell</option>
                      <option value="Blauvelt">Blauvelt</option>
                      <option value="Bloomfield">Bloomfield</option>
                      <option value="Blue Point">Blue Point</option>
                      <option value="Bohemia">Bohemia</option>
                      <option value="Bolivar">Bolivar</option>
                      <option value="Bolivar">Bolivar</option>
                      <option value="Boonville">Boonville</option>
                      <option value="Borough of Queens">Borough of Queens</option>
                      <option value="Brentwood">Brentwood</option>
                      <option value="Brewerton">Brewerton</option>
                      <option value="Brewster">Brewster</option>
                      <option value="Brewster Hill">Brewster Hill</option>
                      <option value="Briarcliff Manor">Briarcliff Manor</option>
                      <option value="Bridgehampton">Bridgehampton</option>
                      <option value="Bridgeport">Bridgeport</option>
                      <option value="Bridgeport">Bridgeport</option>
                      <option value="Brighton">Brighton</option>
                      <option value="Brightwaters">Brightwaters</option>
                      <option value="Brinckerhoff">Brinckerhoff</option>
                      <option value="Broadalbin">Broadalbin</option>
                      <option value="Brockport">Brockport</option>
                      <option value="Brocton">Brocton</option>
                      <option value="Bronxville">Bronxville</option>
                      <option value="Brookhaven">Brookhaven</option>
                      <option value="Brooklyn">Brooklyn</option>
                      <option value="Brookville">Brookville</option>
                      <option value="Brownville">Brownville</option>
                      <option value="Brownville">Brownville</option>
                      <option value="Buchanan">Buchanan</option>
                      <option value="Buffalo">Buffalo</option>
                      <option value="Cairo">Cairo</option>
                      <option value="Calcium">Calcium</option>
                      <option value="Caledonia">Caledonia</option>
                      <option value="Calverton">Calverton</option>
                      <option value="Cambridge">Cambridge</option>
                      <option value="Camden">Camden</option>
                      <option value="Camillus">Camillus</option>
                      <option value="Canajoharie">Canajoharie</option>
                      <option value="Canandaigua">Canandaigua</option>
                      <option value="Canastota">Canastota</option>
                      <option value="Canisteo">Canisteo</option>
                      <option value="Canton">Canton</option>
                      <option value="Carle Place">Carle Place</option>
                      <option value="Carmel">Carmel</option>
                      <option value="Carmel Hamlet">Carmel Hamlet</option>
                      <option value="Carthage">Carthage</option>
                      <option value="Castile">Castile</option>
                      <option value="Castleton-on-Hudson">Castleton-on-Hudson</option>
                      <option value="Catskill">Catskill</option>
                      <option value="Cattaraugus">Cattaraugus</option>
                      <option value="Cayuga Heights">Cayuga Heights</option>
                      <option value="Cazenovia">Cazenovia</option>
                      <option value="Cedarhurst">Cedarhurst</option>
                      <option value="Celoron">Celoron</option>
                      <option value="Center Moriches">Center Moriches</option>
                      <option value="Centereach">Centereach</option>
                      <option value="Centerport">Centerport</option>
                      <option value="Centerport">Centerport</option>
                      <option value="Central Islip">Central Islip</option>
                      <option value="Central Square">Central Square</option>
                      <option value="Central Valley">Central Valley</option>
                      <option value="Chadwicks">Chadwicks</option>
                      <option value="Champlain">Champlain</option>
                      <option value="Chappaqua">Chappaqua</option>
                      <option value="Chatham">Chatham</option>
                      <option value="Cheektowaga">Cheektowaga</option>
                      <option value="Chenango Bridge">Chenango Bridge</option>
                      <option value="Chester">Chester</option>
                      <option value="Chestnut Ridge">Chestnut Ridge</option>
                      <option value="Chittenango">Chittenango</option>
                      <option value="Churchville">Churchville</option>
                      <option value="Clarence">Clarence</option>
                      <option value="Clarence Center">Clarence Center</option>
                      <option value="Clark Mills">Clark Mills</option>
                      <option value="Clarkson">Clarkson</option>
                      <option value="Clayton">Clayton</option>
                      <option value="Clifton Springs">Clifton Springs</option>
                      <option value="Clinton">Clinton</option>
                      <option value="Clintondale">Clintondale</option>
                      <option value="Clyde">Clyde</option>
                      <option value="Clymer">Clymer</option>
                      <option value="Cobleskill">Cobleskill</option>
                      <option value="Cohoes">Cohoes</option>
                      <option value="Cold Spring">Cold Spring</option>
                      <option value="Cold Spring Harbor">Cold Spring Harbor</option>
                      <option value="Colonie">Colonie</option>
                      <option value="Commack">Commack</option>
                      <option value="Coney Island">Coney Island</option>
                      <option value="Congers">Congers</option>
                      <option value="Constantia">Constantia</option>
                      <option value="Cooperstown">Cooperstown</option>
                      <option value="Copiague">Copiague</option>
                      <option value="Coram">Coram</option>
                      <option value="Corinth">Corinth</option>
                      <option value="Corning">Corning</option>
                      <option value="Cornwall-on-Hudson">Cornwall-on-Hudson</option>
                      <option value="Cortland">Cortland</option>
                      <option value="Cortland West">Cortland West</option>
                      <option value="Country Knolls">Country Knolls</option>
                      <option value="Coxsackie">Coxsackie</option>
                      <option value="Crompond">Crompond</option>
                      <option value="Croton-on-Hudson">Croton-on-Hudson</option>
                      <option value="Crown Heights">Crown Heights</option>
                      <option value="Crugers">Crugers</option>
                      <option value="Cuba">Cuba</option>
                      <option value="Cumberland Head">Cumberland Head</option>
                      <option value="Cutchogue">Cutchogue</option>
                      <option value="Dannemora">Dannemora</option>
                      <option value="Dansville">Dansville</option>
                      <option value="Deer Park">Deer Park</option>
                      <option value="Delevan">Delevan</option>
                      <option value="Delhi">Delhi</option>
                      <option value="Delmar">Delmar</option>
                      <option value="Depew">Depew</option>
                      <option value="Deposit">Deposit</option>
                      <option value="Dexter">Dexter</option>
                      <option value="Dix Hills">Dix Hills</option>
                      <option value="Dobbs Ferry">Dobbs Ferry</option>
                      <option value="Dolgeville">Dolgeville</option>
                      <option value="Dover Plains">Dover Plains</option>
                      <option value="Dryden">Dryden</option>
                      <option value="Dundee">Dundee</option>
                      <option value="Dunkirk">Dunkirk</option>
                      <option value="East Atlantic Beach">East Atlantic Beach</option>
                      <option value="East Aurora">East Aurora</option>
                      <option value="East Farmingdale">East Farmingdale</option>
                      <option value="East Garden City">East Garden City</option>
                      <option value="East Glenville">East Glenville</option>
                      <option value="East Greenbush">East Greenbush</option>
                      <option value="East Hampton">East Hampton</option>
                      <option value="East Hampton North">East Hampton North</option>
                      <option value="East Hills">East Hills</option>
                      <option value="East Islip">East Islip</option>
                      <option value="East Ithaca">East Ithaca</option>
                      <option value="East Massapequa">East Massapequa</option>
                      <option value="East Meadow">East Meadow</option>
                      <option value="East Moriches">East Moriches</option>
                      <option value="East New York">East New York</option>
                      <option value="East Northport">East Northport</option>
                      <option value="East Norwich">East Norwich</option>
                      <option value="East Patchogue">East Patchogue</option>
                      <option value="East Quogue">East Quogue</option>
                      <option value="East Rochester">East Rochester</option>
                      <option value="East Rockaway">East Rockaway</option>
                      <option value="East Setauket">East Setauket</option>
                      <option value="East Shoreham">East Shoreham</option>
                      <option value="East Syracuse">East Syracuse</option>
                      <option value="East Williston">East Williston</option>
                      <option value="Eastchester">Eastchester</option>
                      <option value="Eastport">Eastport</option>
                      <option value="Eatons Neck">Eatons Neck</option>
                      <option value="Eden">Eden</option>
                      <option value="Eden">Eden</option>
                      <option value="Eggertsville">Eggertsville</option>
                      <option value="Elbridge">Elbridge</option>
                      <option value="Elizabethtown">Elizabethtown</option>
                      <option value="Ellenville">Ellenville</option>
                      <option value="Elma Center">Elma Center</option>
                      <option value="Elmira">Elmira</option>
                      <option value="Elmira Heights">Elmira Heights</option>
                      <option value="Elmont">Elmont</option>
                      <option value="Elmsford">Elmsford</option>
                      <option value="Elwood">Elwood</option>
                      <option value="Endicott">Endicott</option>
                      <option value="Endwell">Endwell</option>
                      <option value="Fairmount">Fairmount</option>
                      <option value="Fairport">Fairport</option>
                      <option value="Fairview">Fairview</option>
                      <option value="Fairview">Fairview</option>
                      <option value="Falconer">Falconer</option>
                      <option value="Farmingdale">Farmingdale</option>
                      <option value="Farmingville">Farmingville</option>
                      <option value="Fayetteville">Fayetteville</option>
                      <option value="Firthcliffe">Firthcliffe</option>
                      <option value="Fishkill">Fishkill</option>
                      <option value="Flanders">Flanders</option>
                      <option value="Floral Park">Floral Park</option>
                      <option value="Florida">Florida</option>
                      <option value="Flower Hill">Flower Hill</option>
                      <option value="Fonda">Fonda</option>
                      <option value="Fort Drum">Fort Drum</option>
                      <option value="Fort Edward">Fort Edward</option>
                      <option value="Fort Montgomery">Fort Montgomery</option>
                      <option value="Fort Plain">Fort Plain</option>
                      <option value="Fort Salonga">Fort Salonga</option>
                      <option value="Frankfort">Frankfort</option>
                      <option value="Franklin Square">Franklin Square</option>
                      <option value="Franklinville">Franklinville</option>
                      <option value="Fredonia">Fredonia</option>
                      <option value="Freeport">Freeport</option>
                      <option value="Frewsburg">Frewsburg</option>
                      <option value="Friendship">Friendship</option>
                      <option value="Fulton">Fulton</option>
                      <option value="Galeville">Galeville</option>
                      <option value="Gang Mills">Gang Mills</option>
                      <option value="Garden City">Garden City</option>
                      <option value="Garden City Park">Garden City Park</option>
                      <option value="Garden City South">Garden City South</option>
                      <option value="Gardnertown">Gardnertown</option>
                      <option value="Gasport">Gasport</option>
                      <option value="Geneseo">Geneseo</option>
                      <option value="Geneva">Geneva</option>
                      <option value="Glasco">Glasco</option>
                      <option value="Glen Cove">Glen Cove</option>
                      <option value="Glen Head">Glen Head</option>
                      <option value="Glens Falls">Glens Falls</option>
                      <option value="Glens Falls North">Glens Falls North</option>
                      <option value="Glenwood Landing">Glenwood Landing</option>
                      <option value="Gloversville">Gloversville</option>
                      <option value="Goldens Bridge">Goldens Bridge</option>
                      <option value="Gordon Heights">Gordon Heights</option>
                      <option value="Goshen">Goshen</option>
                      <option value="Gouverneur">Gouverneur</option>
                      <option value="Gowanda">Gowanda</option>
                      <option value="Grandyle Village">Grandyle Village</option>
                      <option value="Granville">Granville</option>
                      <option value="Great Neck">Great Neck</option>
                      <option value="Great Neck Estates">Great Neck Estates</option>
                      <option value="Great Neck Gardens">Great Neck Gardens</option>
                      <option value="Great Neck Plaza">Great Neck Plaza</option>
                      <option value="Great River">Great River</option>
                      <option value="Greece">Greece</option>
                      <option value="Green Island">Green Island</option>
                      <option value="Greenburgh">Greenburgh</option>
                      <option value="Greene">Greene</option>
                      <option value="Greenlawn">Greenlawn</option>
                      <option value="Greenport">Greenport</option>
                      <option value="Greenport West">Greenport West</option>
                      <option value="Greenvale">Greenvale</option>
                      <option value="Greenville">Greenville</option>
                      <option value="Greenwich">Greenwich</option>
                      <option value="Greenwood Lake">Greenwood Lake</option>
                      <option value="Groton">Groton</option>
                      <option value="Hadley">Hadley</option>
                      <option value="Hagaman">Hagaman</option>
                      <option value="Halesite">Halesite</option>
                      <option value="Hamburg">Hamburg</option>
                      <option value="Hamilton">Hamilton</option>
                      <option value="Hamlin">Hamlin</option>
                      <option value="Hampton Bays">Hampton Bays</option>
                      <option value="Hampton Manor">Hampton Manor</option>
                      <option value="Hancock">Hancock</option>
                      <option value="Hannawa Falls">Hannawa Falls</option>
                      <option value="Harbor Isle">Harbor Isle</option>
                      <option value="Harriman">Harriman</option>
                      <option value="Harris Hill">Harris Hill</option>
                      <option value="Harrison">Harrison</option>
                      <option value="Hartsdale">Hartsdale</option>
                      <option value="Hastings-on-Hudson">Hastings-on-Hudson</option>
                      <option value="Hauppauge">Hauppauge</option>
                      <option value="Haverstraw">Haverstraw</option>
                      <option value="Haviland">Haviland</option>
                      <option value="Hawthorne">Hawthorne</option>
                      <option value="Head of the Harbor">Head of the Harbor</option>
                      <option value="Hempstead">Hempstead</option>
                      <option value="Heritage Hills">Heritage Hills</option>
                      <option value="Herkimer">Herkimer</option>
                      <option value="Herricks">Herricks</option>
                      <option value="Hewlett">Hewlett</option>
                      <option value="Hewlett Harbor">Hewlett Harbor</option>
                      <option value="Hicksville">Hicksville</option>
                      <option value="Highland">Highland</option>
                      <option value="Highland Falls">Highland Falls</option>
                      <option value="Highland Mills">Highland Mills</option>
                      <option value="Hillcrest">Hillcrest</option>
                      <option value="Hillside Lake">Hillside Lake</option>
                      <option value="Hilton">Hilton</option>
                      <option value="Holbrook">Holbrook</option>
                      <option value="Holcomb">Holcomb</option>
                      <option value="Holland">Holland</option>
                      <option value="Holley">Holley</option>
                      <option value="Holtsville">Holtsville</option>
                      <option value="Homer">Homer</option>
                      <option value="Honeoye Falls">Honeoye Falls</option>
                      <option value="Hoosick Falls">Hoosick Falls</option>
                      <option value="Hornell">Hornell</option>
                      <option value="Horseheads">Horseheads</option>
                      <option value="Horseheads North">Horseheads North</option>
                      <option value="Houghton">Houghton</option>
                      <option value="Hudson">Hudson</option>
                      <option value="Hudson Falls">Hudson Falls</option>
                      <option value="Huntington">Huntington</option>
                      <option value="Huntington Bay">Huntington Bay</option>
                      <option value="Huntington Station">Huntington Station</option>
                      <option value="Hurley">Hurley</option>
                      <option value="Hyde Park">Hyde Park</option>
                      <option value="Ilion">Ilion</option>
                      <option value="Inwood">Inwood</option>
                      <option value="Inwood">Inwood</option>
                      <option value="Irondequoit">Irondequoit</option>
                      <option value="Irvington">Irvington</option>
                      <option value="Island Park">Island Park</option>
                      <option value="Islandia">Islandia</option>
                      <option value="Islip">Islip</option>
                      <option value="Islip Terrace">Islip Terrace</option>
                      <option value="Ithaca">Ithaca</option>
                      <option value="Jamaica">Jamaica</option>
                      <option value="Jamesport">Jamesport</option>
                      <option value="Jamestown">Jamestown</option>
                      <option value="Jamestown West">Jamestown West</option>
                      <option value="Jefferson Heights">Jefferson Heights</option>
                      <option value="Jefferson Valley-Yorktown">Jefferson Valley-Yorktown</option>
                      <option value="Jericho">Jericho</option>
                      <option value="Johnson City">Johnson City</option>
                      <option value="Johnstown">Johnstown</option>
                      <option value="Jordan">Jordan</option>
                      <option value="Kaser">Kaser</option>
                      <option value="Katonah">Katonah</option>
                      <option value="Keeseville">Keeseville</option>
                      <option value="Kenmore">Kenmore</option>
                      <option value="Kensington">Kensington</option>
                      <option value="Kerhonkson">Kerhonkson</option>
                      <option value="Keuka Park">Keuka Park</option>
                      <option value="Kinderhook">Kinderhook</option>
                      <option value="Kings Park">Kings Park</option>
                      <option value="Kings Point">Kings Point</option>
                      <option value="Kingston">Kingston</option>
                      <option value="Kiryas Joel">Kiryas Joel</option>
                      <option value="Lackawanna">Lackawanna</option>
                      <option value="Lake Carmel">Lake Carmel</option>
                      <option value="Lake Erie Beach">Lake Erie Beach</option>
                      <option value="Lake Grove">Lake Grove</option>
                      <option value="Lake Katrine">Lake Katrine</option>
                      <option value="Lake Mohegan">Lake Mohegan</option>
                      <option value="Lake Placid">Lake Placid</option>
                      <option value="Lake Pleasant">Lake Pleasant</option>
                      <option value="Lake Ronkonkoma">Lake Ronkonkoma</option>
                      <option value="Lake Success">Lake Success</option>
                      <option value="Lakeland">Lakeland</option>
                      <option value="Lakeview">Lakeview</option>
                      <option value="Lakeview">Lakeview</option>
                      <option value="Lakewood">Lakewood</option>
                      <option value="Lancaster">Lancaster</option>
                      <option value="Lansing">Lansing</option>
                      <option value="Larchmont">Larchmont</option>
                      <option value="Lattingtown">Lattingtown</option>
                      <option value="Laurel">Laurel</option>
                      <option value="Laurel Hollow">Laurel Hollow</option>
                      <option value="Lawrence">Lawrence</option>
                      <option value="Le Roy">Le Roy</option>
                      <option value="Levittown">Levittown</option>
                      <option value="Lewiston">Lewiston</option>
                      <option value="Liberty">Liberty</option>
                      <option value="Lido Beach">Lido Beach</option>
                      <option value="Lima">Lima</option>
                      <option value="Lincoln Park">Lincoln Park</option>
                      <option value="Lincolndale">Lincolndale</option>
                      <option value="Lindenhurst">Lindenhurst</option>
                      <option value="Little Falls">Little Falls</option>
                      <option value="Little Valley">Little Valley</option>
                      <option value="Liverpool">Liverpool</option>
                      <option value="Livingston Manor">Livingston Manor</option>
                      <option value="Livonia">Livonia</option>
                      <option value="Lloyd Harbor">Lloyd Harbor</option>
                      <option value="Lockport">Lockport</option>
                      <option value="Locust Valley">Locust Valley</option>
                      <option value="Long Beach">Long Beach</option>
                      <option value="Long Island City">Long Island City</option>
                      <option value="Lorenz Park">Lorenz Park</option>
                      <option value="Lowville">Lowville</option>
                      <option value="Lynbrook">Lynbrook</option>
                      <option value="Lyncourt">Lyncourt</option>
                      <option value="Lyons">Lyons</option>
                      <option value="Macedon">Macedon</option>
                      <option value="Mahopac">Mahopac</option>
                      <option value="Malone">Malone</option>
                      <option value="Malverne">Malverne</option>
                      <option value="Mamaroneck">Mamaroneck</option>
                      <option value="Manchester">Manchester</option>
                      <option value="Manhasset">Manhasset</option>
                      <option value="Manhasset Hills">Manhasset Hills</option>
                      <option value="Manhattan">Manhattan</option>
                      <option value="Manlius">Manlius</option>
                      <option value="Manorhaven">Manorhaven</option>
                      <option value="Manorville">Manorville</option>
                      <option value="Manorville">Manorville</option>
                      <option value="Marcellus">Marcellus</option>
                      <option value="Marion">Marion</option>
                      <option value="Marlboro">Marlboro</option>
                      <option value="Massapequa">Massapequa</option>
                      <option value="Massapequa Park">Massapequa Park</option>
                      <option value="Massena">Massena</option>
                      <option value="Mastic">Mastic</option>
                      <option value="Mastic Beach">Mastic Beach</option>
                      <option value="Mattituck">Mattituck</option>
                      <option value="Mattydale">Mattydale</option>
                      <option value="Maybrook">Maybrook</option>
                      <option value="Mayville">Mayville</option>
                      <option value="McGraw">McGraw</option>
                      <option value="McKownville">McKownville</option>
                      <option value="Mechanicstown">Mechanicstown</option>
                      <option value="Mechanicville">Mechanicville</option>
                      <option value="Medford">Medford</option>
                      <option value="Medina">Medina</option>
                      <option value="Melrose Park">Melrose Park</option>
                      <option value="Melville">Melville</option>
                      <option value="Menands">Menands</option>
                      <option value="Merrick">Merrick</option>
                      <option value="Mexico">Mexico</option>
                      <option value="Middle Island">Middle Island</option>
                      <option value="Middleburgh">Middleburgh</option>
                      <option value="Middleport">Middleport</option>
                      <option value="Middleport">Middleport</option>
                      <option value="Middletown">Middletown</option>
                      <option value="Millbrook">Millbrook</option>
                      <option value="Miller Place">Miller Place</option>
                      <option value="Milton">Milton</option>
                      <option value="Milton">Milton</option>
                      <option value="Mineola">Mineola</option>
                      <option value="Minetto">Minetto</option>
                      <option value="Mineville">Mineville</option>
                      <option value="Minoa">Minoa</option>
                      <option value="Mohawk">Mohawk</option>
                      <option value="Monroe">Monroe</option>
                      <option value="Monsey">Monsey</option>
                      <option value="Montauk">Montauk</option>
                      <option value="Montebello">Montebello</option>
                      <option value="Montgomery">Montgomery</option>
                      <option value="Monticello">Monticello</option>
                      <option value="Montour Falls">Montour Falls</option>
                      <option value="Montrose">Montrose</option>
                      <option value="Moravia">Moravia</option>
                      <option value="Moriches">Moriches</option>
                      <option value="Morrisonville">Morrisonville</option>
                      <option value="Morrisville">Morrisville</option>
                      <option value="Mount Ivy">Mount Ivy</option>
                      <option value="Mount Kisco">Mount Kisco</option>
                      <option value="Mount Morris">Mount Morris</option>
                      <option value="Mount Sinai">Mount Sinai</option>
                      <option value="Mount Vernon">Mount Vernon</option>
                      <option value="Mountain Lodge Park">Mountain Lodge Park</option>
                      <option value="Munsey Park">Munsey Park</option>
                      <option value="Munsons Corners">Munsons Corners</option>
                      <option value="Muttontown">Muttontown</option>
                      <option value="Myers Corner">Myers Corner</option>
                      <option value="Nanuet">Nanuet</option>
                      <option value="Napanoch">Napanoch</option>
                      <option value="Naples">Naples</option>
                      <option value="Nassau">Nassau</option>
                      <option value="Nedrow">Nedrow</option>
                      <option value="Nesconset">Nesconset</option>
                      <option value="New Berlin">New Berlin</option>
                      <option value="New Cassel">New Cassel</option>
                      <option value="New City">New City</option>
                      <option value="New Hartford">New Hartford</option>
                      <option value="New Hempstead">New Hempstead</option>
                      <option value="New Hyde Park">New Hyde Park</option>
                      <option value="New Paltz">New Paltz</option>
                      <option value="New Rochelle">New Rochelle</option>
                      <option value="New Square">New Square</option>
                      <option value="New Windsor">New Windsor</option>
                      <option value="New York">New York</option>
                      <option value="New York City">New York City</option>
                      <option value="New York Mills">New York Mills</option>
                      <option value="Newark">Newark</option>
                      <option value="Newburgh">Newburgh</option>
                      <option value="Newfane">Newfane</option>
                      <option value="Niagara Falls">Niagara Falls</option>
                      <option value="Niskayuna">Niskayuna</option>
                      <option value="Nissequogue">Nissequogue</option>
                      <option value="Niverville">Niverville</option>
                      <option value="Norfolk">Norfolk</option>
                      <option value="North Amityville">North Amityville</option>
                      <option value="North Babylon">North Babylon</option>
                      <option value="North Ballston Spa">North Ballston Spa</option>
                      <option value="North Bay Shore">North Bay Shore</option>
                      <option value="North Bellmore">North Bellmore</option>
                      <option value="North Bellport">North Bellport</option>
                      <option value="North Boston">North Boston</option>
                      <option value="North Collins">North Collins</option>
                      <option value="North Gates">North Gates</option>
                      <option value="North Great River">North Great River</option>
                      <option value="North Hills">North Hills</option>
                      <option value="North Lindenhurst">North Lindenhurst</option>
                      <option value="North Massapequa">North Massapequa</option>
                      <option value="North Merrick">North Merrick</option>
                      <option value="North New Hyde Park">North New Hyde Park</option>
                      <option value="North Patchogue">North Patchogue</option>
                      <option value="North Sea">North Sea</option>
                      <option value="North Syracuse">North Syracuse</option>
                      <option value="North Tonawanda">North Tonawanda</option>
                      <option value="North Valley Stream">North Valley Stream</option>
                      <option value="North Wantagh">North Wantagh</option>
                      <option value="Northeast Ithaca">Northeast Ithaca</option>
                      <option value="Northport">Northport</option>
                      <option value="Northville">Northville</option>
                      <option value="Northville">Northville</option>
                      <option value="Northwest Harbor">Northwest Harbor</option>
                      <option value="Northwest Ithaca">Northwest Ithaca</option>
                      <option value="Norwich">Norwich</option>
                      <option value="Norwood">Norwood</option>
                      <option value="Noyack">Noyack</option>
                      <option value="Nunda">Nunda</option>
                      <option value="Nyack">Nyack</option>
                      <option value="Oakdale">Oakdale</option>
                      <option value="Oakdale">Oakdale</option>
                      <option value="Oakfield">Oakfield</option>
                      <option value="Oceanside">Oceanside</option>
                      <option value="Ogdensburg">Ogdensburg</option>
                      <option value="Olcott">Olcott</option>
                      <option value="Old Bethpage">Old Bethpage</option>
                      <option value="Old Brookville">Old Brookville</option>
                      <option value="Old Westbury">Old Westbury</option>
                      <option value="Olean">Olean</option>
                      <option value="Oneida">Oneida</option>
                      <option value="Oneonta">Oneonta</option>
                      <option value="Ontario">Ontario</option>
                      <option value="Orange Lake">Orange Lake</option>
                      <option value="Orangeburg">Orangeburg</option>
                      <option value="Orchard Park">Orchard Park</option>
                      <option value="Oriskany">Oriskany</option>
                      <option value="Ossining">Ossining</option>
                      <option value="Oswego">Oswego</option>
                      <option value="Otego">Otego</option>
                      <option value="Otisville">Otisville</option>
                      <option value="Owego">Owego</option>
                      <option value="Oxford">Oxford</option>
                      <option value="Oyster Bay">Oyster Bay</option>
                      <option value="Oyster Bay Cove">Oyster Bay Cove</option>
                      <option value="Painted Post">Painted Post</option>
                      <option value="Palenville">Palenville</option>
                      <option value="Palmyra">Palmyra</option>
                      <option value="Patchogue">Patchogue</option>
                      <option value="Pawling">Pawling</option>
                      <option value="Peach Lake">Peach Lake</option>
                      <option value="Pearl River">Pearl River</option>
                      <option value="Peekskill">Peekskill</option>
                      <option value="Pelham">Pelham</option>
                      <option value="Pelham Manor">Pelham Manor</option>
                      <option value="Penn Yan">Penn Yan</option>
                      <option value="Perry">Perry</option>
                      <option value="Perth">Perth</option>
                      <option value="Peru">Peru</option>
                      <option value="Peru">Peru</option>
                      <option value="Phelps">Phelps</option>
                      <option value="Philadelphia">Philadelphia</option>
                      <option value="Philmont">Philmont</option>
                      <option value="Phoenix">Phoenix</option>
                      <option value="Piermont">Piermont</option>
                      <option value="Pine Bush">Pine Bush</option>
                      <option value="Pine Plains">Pine Plains</option>
                      <option value="Pittsford">Pittsford</option>
                      <option value="Plainedge">Plainedge</option>
                      <option value="Plainview">Plainview</option>
                      <option value="Plandome">Plandome</option>
                      <option value="Plandome Heights">Plandome Heights</option>
                      <option value="Plattekill">Plattekill</option>
                      <option value="Plattsburgh">Plattsburgh</option>
                      <option value="Plattsburgh West">Plattsburgh West</option>
                      <option value="Pleasant Valley">Pleasant Valley</option>
                      <option value="Pleasantville">Pleasantville</option>
                      <option value="Poestenkill">Poestenkill</option>
                      <option value="Point Lookout">Point Lookout</option>
                      <option value="Pomona">Pomona</option>
                      <option value="Port Byron">Port Byron</option>
                      <option value="Port Chester">Port Chester</option>
                      <option value="Port Dickinson">Port Dickinson</option>
                      <option value="Port Ewen">Port Ewen</option>
                      <option value="Port Henry">Port Henry</option>
                      <option value="Port Jefferson">Port Jefferson</option>
                      <option value="Port Jefferson Station">Port Jefferson Station</option>
                      <option value="Port Jervis">Port Jervis</option>
                      <option value="Port Washington">Port Washington</option>
                      <option value="Port Washington North">Port Washington North</option>
                      <option value="Portville">Portville</option>
                      <option value="Potsdam">Potsdam</option>
                      <option value="Poughkeepsie">Poughkeepsie</option>
                      <option value="Pound Ridge">Pound Ridge</option>
                      <option value="Pulaski">Pulaski</option>
                      <option value="Purchase">Purchase</option>
                      <option value="Putnam Lake">Putnam Lake</option>
                      <option value="Queensbury">Queensbury</option>
                      <option value="Randolph">Randolph</option>
                      <option value="Ransomville">Ransomville</option>
                      <option value="Rapids">Rapids</option>
                      <option value="Ravena">Ravena</option>
                      <option value="Red Hook">Red Hook</option>
                      <option value="Red Oaks Mill">Red Oaks Mill</option>
                      <option value="Remsenburg-Speonk">Remsenburg-Speonk</option>
                      <option value="Rensselaer">Rensselaer</option>
                      <option value="Rhinebeck">Rhinebeck</option>
                      <option value="Richfield Springs">Richfield Springs</option>
                      <option value="Ridge">Ridge</option>
                      <option value="Riverhead">Riverhead</option>
                      <option value="Riverside">Riverside</option>
                      <option value="Rochester">Rochester</option>
                      <option value="Rock Hill">Rock Hill</option>
                      <option value="Rockville Centre">Rockville Centre</option>
                      <option value="Rocky Point">Rocky Point</option>
                      <option value="Roessleville">Roessleville</option>
                      <option value="Rome">Rome</option>
                      <option value="Ronkonkoma">Ronkonkoma</option>
                      <option value="Roosevelt">Roosevelt</option>
                      <option value="Rosendale Village">Rosendale Village</option>
                      <option value="Roslyn">Roslyn</option>
                      <option value="Roslyn Estates">Roslyn Estates</option>
                      <option value="Roslyn Harbor">Roslyn Harbor</option>
                      <option value="Roslyn Heights">Roslyn Heights</option>
                      <option value="Rotterdam">Rotterdam</option>
                      <option value="Rouses Point">Rouses Point</option>
                      <option value="Rye">Rye</option>
                      <option value="Rye Brook">Rye Brook</option>
                      <option value="Sackets Harbor">Sackets Harbor</option>
                      <option value="Sag Harbor">Sag Harbor</option>
                      <option value="Saint Bonaventure">Saint Bonaventure</option>
                      <option value="Saint James">Saint James</option>
                      <option value="Saint Johnsville">Saint Johnsville</option>
                      <option value="Salamanca">Salamanca</option>
                      <option value="Salisbury">Salisbury</option>
                      <option value="Sanborn">Sanborn</option>
                      <option value="Sands Point">Sands Point</option>
                      <option value="Saranac Lake">Saranac Lake</option>
                      <option value="Saratoga Springs">Saratoga Springs</option>
                      <option value="Saugerties">Saugerties</option>
                      <option value="Saugerties South">Saugerties South</option>
                      <option value="Sayville">Sayville</option>
                      <option value="Scarsdale">Scarsdale</option>
                      <option value="Schenectady">Schenectady</option>
                      <option value="Schoharie">Schoharie</option>
                      <option value="Schuylerville">Schuylerville</option>
                      <option value="Scotchtown">Scotchtown</option>
                      <option value="Scotia">Scotia</option>
                      <option value="Scottsville">Scottsville</option>
                      <option value="Sea Cliff">Sea Cliff</option>
                      <option value="Seaford">Seaford</option>
                      <option value="Searingtown">Searingtown</option>
                      <option value="Selden">Selden</option>
                      <option value="Seneca Falls">Seneca Falls</option>
                      <option value="Seneca Knolls">Seneca Knolls</option>
                      <option value="Setauket-East Setauket">Setauket-East Setauket</option>
                      <option value="Shelter Island">Shelter Island</option>
                      <option value="Shelter Island Heights">Shelter Island Heights</option>
                      <option value="Shenorock">Shenorock</option>
                      <option value="Sherburne">Sherburne</option>
                      <option value="Sherrill">Sherrill</option>
                      <option value="Shinnecock Hills">Shinnecock Hills</option>
                      <option value="Shirley">Shirley</option>
                      <option value="Shokan">Shokan</option>
                      <option value="Shortsville">Shortsville</option>
                      <option value="Shrub Oak">Shrub Oak</option>
                      <option value="Sidney">Sidney</option>
                      <option value="Silver Creek">Silver Creek</option>
                      <option value="Skaneateles">Skaneateles</option>
                      <option value="Sleepy Hollow">Sleepy Hollow</option>
                      <option value="Sloan">Sloan</option>
                      <option value="Sloatsburg">Sloatsburg</option>
                      <option value="Smithtown">Smithtown</option>
                      <option value="Sodus">Sodus</option>
                      <option value="Solvay">Solvay</option>
                      <option value="Sound Beach">Sound Beach</option>
                      <option value="South Blooming Grove">South Blooming Grove</option>
                      <option value="South Corning">South Corning</option>
                      <option value="South Fallsburg">South Fallsburg</option>
                      <option value="South Farmingdale">South Farmingdale</option>
                      <option value="South Floral Park">South Floral Park</option>
                      <option value="South Glens Falls">South Glens Falls</option>
                      <option value="South Hempstead">South Hempstead</option>
                      <option value="South Hill">South Hill</option>
                      <option value="South Huntington">South Huntington</option>
                      <option value="South Lockport">South Lockport</option>
                      <option value="South Nyack">South Nyack</option>
                      <option value="South Valley Stream">South Valley Stream</option>
                      <option value="Southampton">Southampton</option>
                      <option value="Southold">Southold</option>
                      <option value="Southport">Southport</option>
                      <option value="Spackenkill">Spackenkill</option>
                      <option value="Sparkill">Sparkill</option>
                      <option value="Spencerport">Spencerport</option>
                      <option value="Spring Valley">Spring Valley</option>
                      <option value="Springs">Springs</option>
                      <option value="Springville">Springville</option>
                      <option value="Stamford">Stamford</option>
                      <option value="Staten Island">Staten Island</option>
                      <option value="Stewart Manor">Stewart Manor</option>
                      <option value="Stillwater">Stillwater</option>
                      <option value="Stone Ridge">Stone Ridge</option>
                      <option value="Stony Brook">Stony Brook</option>
                      <option value="Stony Point">Stony Point</option>
                      <option value="Stottville">Stottville</option>
                      <option value="Suffern">Suffern</option>
                      <option value="Sylvan Beach">Sylvan Beach</option>
                      <option value="Syosset">Syosset</option>
                      <option value="Syracuse">Syracuse</option>
                      <option value="Tappan">Tappan</option>
                      <option value="Tarrytown">Tarrytown</option>
                      <option value="Terryville">Terryville</option>
                      <option value="The Bronx">The Bronx</option>
                      <option value="Thiells">Thiells</option>
                      <option value="Thomaston">Thomaston</option>
                      <option value="Thornwood">Thornwood</option>
                      <option value="Ticonderoga">Ticonderoga</option>
                      <option value="Tillson">Tillson</option>
                      <option value="Tivoli">Tivoli</option>
                      <option value="Tonawanda">Tonawanda</option>
                      <option value="Tonawanda">Tonawanda</option>
                      <option value="Town Line">Town Line</option>
                      <option value="Tribes Hill">Tribes Hill</option>
                      <option value="Troy">Troy</option>
                      <option value="Trumansburg">Trumansburg</option>
                      <option value="Tuckahoe">Tuckahoe</option>
                      <option value="Tuckahoe">Tuckahoe</option>
                      <option value="Tupper Lake">Tupper Lake</option>
                      <option value="Unadilla">Unadilla</option>
                      <option value="Union Springs">Union Springs</option>
                      <option value="Uniondale">Uniondale</option>
                      <option value="University Gardens">University Gardens</option>
                      <option value="Upper Brookville">Upper Brookville</option>
                      <option value="Upper Nyack">Upper Nyack</option>
                      <option value="Utica">Utica</option>
                      <option value="Vails Gate">Vails Gate</option>
                      <option value="Valatie">Valatie</option>
                      <option value="Valhalla">Valhalla</option>
                      <option value="Valley Cottage">Valley Cottage</option>
                      <option value="Valley Stream">Valley Stream</option>
                      <option value="Vernon">Vernon</option>
                      <option value="Verplanck">Verplanck</option>
                      <option value="Victor">Victor</option>
                      <option value="Village Green">Village Green</option>
                      <option value="Village of the Branch">Village of the Branch</option>
                      <option value="Viola">Viola</option>
                      <option value="Voorheesville">Voorheesville</option>
                      <option value="Wading River">Wading River</option>
                      <option value="Walden">Walden</option>
                      <option value="Wallkill">Wallkill</option>
                      <option value="Walton">Walton</option>
                      <option value="Walton Park">Walton Park</option>
                      <option value="Wampsville">Wampsville</option>
                      <option value="Wanakah">Wanakah</option>
                      <option value="Wantagh">Wantagh</option>
                      <option value="Wappingers Falls">Wappingers Falls</option>
                      <option value="Warrensburg">Warrensburg</option>
                      <option value="Warsaw">Warsaw</option>
                      <option value="Warwick">Warwick</option>
                      <option value="Washington Heights">Washington Heights</option>
                      <option value="Washington Mills">Washington Mills</option>
                      <option value="Washingtonville">Washingtonville</option>
                      <option value="Water Mill">Water Mill</option>
                      <option value="Water Mill">Water Mill</option>
                      <option value="Waterford">Waterford</option>
                      <option value="Waterloo">Waterloo</option>
                      <option value="Watertown">Watertown</option>
                      <option value="Waterville">Waterville</option>
                      <option value="Watervliet">Watervliet</option>
                      <option value="Watkins Glen">Watkins Glen</option>
                      <option value="Waverly">Waverly</option>
                      <option value="Wayland">Wayland</option>
                      <option value="Webster">Webster</option>
                      <option value="Weedsport">Weedsport</option>
                      <option value="Wellsville">Wellsville</option>
                      <option value="Wellsville">Wellsville</option>
                      <option value="Wesley Hills">Wesley Hills</option>
                      <option value="West Albany">West Albany</option>
                      <option value="West Babylon">West Babylon</option>
                      <option value="West Bay Shore">West Bay Shore</option>
                      <option value="West Carthage">West Carthage</option>
                      <option value="West Elmira">West Elmira</option>
                      <option value="West End">West End</option>
                      <option value="West Glens Falls">West Glens Falls</option>
                      <option value="West Haverstraw">West Haverstraw</option>
                      <option value="West Hempstead">West Hempstead</option>
                      <option value="West Hills">West Hills</option>
                      <option value="West Hurley">West Hurley</option>
                      <option value="West Islip">West Islip</option>
                      <option value="West Nyack">West Nyack</option>
                      <option value="West Point">West Point</option>
                      <option value="West Sand Lake">West Sand Lake</option>
                      <option value="West Sayville">West Sayville</option>
                      <option value="West Seneca">West Seneca</option>
                      <option value="Westbury">Westbury</option>
                      <option value="Westfield">Westfield</option>
                      <option value="Westhampton">Westhampton</option>
                      <option value="Westhampton Beach">Westhampton Beach</option>
                      <option value="Westmere">Westmere</option>
                      <option value="Weston Mills">Weston Mills</option>
                      <option value="Westons Mills">Westons Mills</option>
                      <option value="Westvale">Westvale</option>
                      <option value="Wheatley Heights">Wheatley Heights</option>
                      <option value="White Plains">White Plains</option>
                      <option value="Whitehall">Whitehall</option>
                      <option value="Whitesboro">Whitesboro</option>
                      <option value="Williamson">Williamson</option>
                      <option value="Williamsville">Williamsville</option>
                      <option value="Williston Park">Williston Park</option>
                      <option value="Wilson">Wilson</option>
                      <option value="Wolcott">Wolcott</option>
                      <option value="Woodbury">Woodbury</option>
                      <option value="Woodbury">Woodbury</option>
                      <option value="Woodmere">Woodmere</option>
                      <option value="Woodstock">Woodstock</option>
                      <option value="Worcester">Worcester</option>
                      <option value="Wurtsboro">Wurtsboro</option>
                      <option value="Wyandanch">Wyandanch</option>
                      <option value="Wynantskill">Wynantskill</option>
                      <option value="Yaphank">Yaphank</option>
                      <option value="Yonkers">Yonkers</option>
                      <option value="Yorkshire">Yorkshire</option>
                      <option value="Yorktown Heights">Yorktown Heights</option>
                      <option value="Yorkville">Yorkville</option>
                      <option value="Youngstown">Youngstown</option>
                      <option value="Zena">Zena</option>
                    </select>
                  </div>
                </div>
                <div class="col-12 col-sm-12 col-md-3 col-lg-2">
                  <button id="searchButton" class="btn d-block w-100">Search</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="defaultIntro pb-5 animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container-fluid">
        <div class="row clearfix justify-content-center">
          <div class="col-12 col-sm-10 col-md-8">
            <h2>Authorized Dealers</h2>
            <p class="text-center">Berd Vay'e is pleased to partner with many of the world’s premier purveyors of luxury accessories and timepieces. To find an authorized dealer near you, please use our search tool to view our stockists.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="defaultDealers animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container">
        <div class="row clearfix">
          @foreach ($dealers as $dealer)
          <div class="col-12 col-sm-6 col-md-6 col-lg-4">
            <div class="dealerBox">
              <h3 class="dealerBox-name text-cream-500">{{$dealer->customer}}</h3>
              <p class="dealerBox-loc"><b><img src="images/contact/icon_loc.png" alt="icon_loc"></b>{!! nl2br($dealer->address) !!}</p>
              <p class="dealerBox-tel"><b><img src="images/contact/icon_call.png" alt="icon_call"></b>{{$dealer->phone}}</p>
              <p class="dealerBox-site"><b><img src="images/contact/icon_site.png" alt="icon_site"></b><a href="http://{{$dealer->website}}" target="_blank">{{$dealer->website}}</a></p>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    @include('contactInfo')
@endsection

@push ('jquery')
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBuu5S5S2I8ORvtF1VMJVdslJDSDzfgM6Q&callback=initMap"></script>

<script type="text/javascript">
    var markers = [];
    var infoWindow;

    function initMap() {
        // pick center coordinates for your map
        var myMapCenter = {lat: 40.785091, lng: -73.968285};

        // create map and say which HTML element it should appear in
        var map = new google.maps.Map(document.getElementById('map'), {
          center: myMapCenter,
          zoom: 4,
            mapTypeId: 'satellite',
            mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
        });

        infoWindow = new google.maps.InfoWindow();
        searchButton = document.getElementById("searchButton").onclick = searchLocations;
        var searchUrl = "{{route('google.maker')}}";
        var stores = '';

        downloadUrl(searchUrl, function(data) {
          stores=JSON.parse(data);
          stores.forEach(function(store){
            markStore(store);
          });
        })

        function downloadUrl(url, callback) {
          var request = window.ActiveXObject ?
              new ActiveXObject('Microsoft.XMLHTTP') :
              new XMLHttpRequest;

          request.onreadystatechange = function() {
            if (request.readyState == 4) {
              request.onreadystatechange = doNothing;
              callback(request.responseText, request.status);
            }
          };

          request.open('GET', url, true);
          request.send(null);
       }

        function markStore(storeInfo){

          // Create a marker and set its position.
          storeInfo.location.lat = parseFloat(storeInfo.location.lat);
          storeInfo.location.lng = parseFloat(storeInfo.location.lng);
          storeInfo.name = storeInfo.customer;
          var marker = new google.maps.Marker({
            map: map,
            position: storeInfo.location,
            title: storeInfo.name
          });

          var name = storeInfo.customer;
          var address = storeInfo.address;
          var website = storeInfo.website;
          var phone = storeInfo.phone;

          createMarker(storeInfo.location, name, address, website, phone);
          // show store info when marker is clicked
          // marker.addListener('click', function(){
          //   showStoreInfo(storeInfo);
          // });
          }

          // show store info in text box
          function showStoreInfo(storeInfo){
          var info_div = document.getElementById('info_div');
          info_div.innerHTML = 'Store name: '
            + storeInfo.name
            + '<br>Hours: ' + storeInfo.hours;
          }

          function doNothing() {}

          function searchLocations() {
            var address = document.getElementById("pac-input").value;
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({address: address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              searchLocationsNear(results[0].geometry.location);
            } else {
              alert(address + ' not found');
            }
         });
    }

    function createOption(name, distance, num) {
        var option = document.createElement("option");
        option.value = num;
        option.innerHTML = name;
        locationSelect.appendChild(option);
    }

    function createMarker(latlng, name, address, website, phone) {
      var html = "<div style='text-align: left'><b>" + name + "</b> <br/>" + address + '<br><br><b>Phone:</b> ' + phone  + '<br><b>Website:</b> ' + website + '</div>';
      var marker = new google.maps.Marker({
        map: map,
        position: latlng
      });
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
      markers.push(marker);
    }

    function clearLocations() {
      infoWindow.close();
      for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
      }
      markers.length = 0;

      locationSelect.innerHTML = "";
      var option = document.createElement("option");
      option.value = "none";
      option.innerHTML = "See all results:";
      locationSelect.appendChild(option);
    }

    function searchLocationsNear(center) {
         clearLocations();

         var radius = 10;
         var searchUrl = "{{route('get.google.maker')}}" + '/?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius;
         downloadUrl(searchUrl, function(data) {
            var markerNodes=JSON.parse(data);
           //var markerNodes = xml.documentElement.getElementsByTagName("marker");
           var bounds = new google.maps.LatLngBounds();
           for (var i = 0; i < markerNodes.length; i++) {
             var id = markerNodes[i].id;
             var name = markerNodes[i].customer;
             var address = markerNodes[i].address;
             var distance = parseFloat(markerNodes[i].distance);
             var website = markerNodes[i].website;
             var phone = markerNodes[i].phone;

             var latlng = new google.maps.LatLng(
                  parseFloat(markerNodes[i].lat),
                  parseFloat(markerNodes[i].lng));

             createOption(name, distance, i);
             createMarker(latlng, name, address, website, phone);
             bounds.extend(latlng);
           }
           map.fitBounds(bounds);
           document.getElementById("selectContainer").style.visibility = "visible";
           locationSelect.onchange = function() {
             var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
             google.maps.event.trigger(markers[markerNum], 'click');
           };
         });
    }

  }
</script>

<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($) {

  $('.dealersmenu').addClass('active');
  $('.rightMenu').html('<img class="imgCenter" src="/images/menu/menu_banner_04.jpg" alt="menu_banner_04">');

setTimeout( function(){
   $('.animateRun').viewportChecker();
	},1000);


			// // HEADER MENU FUNCTION
			// if ($(window).width() <= 768) {
			// 					$(document).on('click', '.mobileMenu', function(e) {
			// 								e.preventDefault();
			// 								var data = $(this).next('.rightShow').slideToggle()
			// 				});
			// } else {
			// 				$('.linkMenu').hover(function(e) {
			// 								e.preventDefault();
			// 								var data = $(this).next('.rightShow').html();
			// 								$('.rightMenu').html(data);
			// 				});
			// }

});
</script>
@endpush