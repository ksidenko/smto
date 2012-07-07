
var map;
var icon;
var geocoder;

function load(marker,shadow)
{	
	if (GBrowserIsCompatible()) {
        geocoder = new GClientGeocoder();
        map = new GMap2(document.getElementById("map"));
        map.addControl(new GSmallMapControl());
        map.addControl(new GMapTypeControl());

        //enables mouse wheel zoom
        map.enableScrollWheelZoom();

		// center map (Europe)
        map.setCenter(new GLatLng(52, 27), 3);
       
        // define marker and shadow files
        markerFile = marker;
        shadowFile = shadow;
    }
}

function searchLocations(searchType, cc) {
    var address = document.getElementById('inputAddress').value;
    var name = document.getElementById('inputName').value;
    //alert ("search type: "+searchType+" CC: "+cc +" name:"+name);
    //alert (address);
    geocoder.getLatLng(address, function(latlng) {
        if (!latlng || searchType == 'name') {
            //alert(address + ' not found');
            searchLocationsByName(name, cc);
        } else {
            searchLocationsNear(latlng, cc);
        }
    });
}

function searchLocationsNear(center ,cc) {
    var radius = document.getElementById('inputRadius').value;
    //alert ("center: "+center+" CC: "+cc+ " Radius: "+radius );
    var searchUrl = 'search/GoogleMapsSearch.php?latitude=' + center.lat() + '&longtitude=' + center.lng() + '&radius=' + radius + '&cc=' + cc + '&search_type=address';
    //alert ("search url: "+searchUrl);
    GDownloadUrl(searchUrl, function(data) {
        var xml = GXml.parse(data);
        var markers = xml.documentElement.getElementsByTagName('marker');
        //alert("markers: "+markers);
        map.clearOverlays();

        var sidebar = document.getElementById('sidebar');
        sidebar.innerHTML = '';
        if (markers.length == 0) {
            sidebar.innerHTML = '<div id="SearchError"><b>Sorry, no results found.</b><br /><span>Please try another search term.</span>';
            map.setCenter(new GLatLng(40, -100), zoom);
            return;
        }

        var bounds = new GLatLngBounds();
        for (var i = 0; i < markers.length; i++) {
            var name = markers[i].getAttribute('name');
            //alert("name: "+name);
            var address = markers[i].getAttribute('street')+"<br />"+markers[i].getAttribute('zip')+" "+markers[i].getAttribute('town')+"<br />"+markers[i].getAttribute('country')+"<br /><br />"
            + markers[i].getAttribute('phone')+"<br />"+markers[i].getAttribute('fax')+"<br /><br /><a href='mailto:"+markers[i].getAttribute('email')+"'>"+markers[i].getAttribute('email')+"</a><br /><a href='http://"+markers[i].getAttribute('url')+"'>"+markers[i].getAttribute('url')+"</a>";
            var distance = parseFloat(markers[i].getAttribute('distance'));
            var point = new GLatLng(parseFloat(markers[i].getAttribute('latitude')),
                parseFloat(markers[i].getAttribute('longtitude')));

            var marker = createMarker(point, name, address);
            map.addOverlay(marker);
            var sidebarEntry = createSidebarEntry(marker, name, address, distance);
            sidebar.appendChild(sidebarEntry);
            bounds.extend(point);
        }
        map.setCenter(bounds.getCenter(), map.getBoundsZoomLevel(bounds));
    });
}

function searchLocationsByName(name, cc) {
    // var cc = '<%= Prado :: getApplication()->getModule('geo_ip')->getCountryCode()%>';
    //var searchUrl = 'http://umf-bikes.com/public/search/GoogleMapsSearch.php?search_type=name&name=' + name + '&cc=' + cc;
    var searchUrl = 'search/GoogleMapsSearch.php?search_type=name&name=' + name + '&cc=' + cc;
    //alert ("search url: "+searchUrl);
    //alert ("name: "+name+" CC: "+cc+ " search url: "+searchUrl);
    GDownloadUrl(searchUrl, function(data) {
        var xml = GXml.parse(data);

        //console.log(xml);

        var markers = xml.documentElement.getElementsByTagName('marker');
        //alert("markers: "+markers);
        //map.clearOverlays();

        var sidebar = document.getElementById('sidebar');
        sidebar.innerHTML = '';
        if (markers.length == 0) {
            sidebar.innerHTML = '<div id="SearchError"><b>Sorry, no results found.</b><br /><span>Please try another search term.</span>';
            map.setCenter(new GLatLng(40, -100), zoom);
            return;
        }

        var bounds = new GLatLngBounds();
        for (var i = 0; i < markers.length; i++) {
            var name = markers[i].getAttribute('name');
            //alert("name: "+name);
            var address = markers[i].getAttribute('street')+"<br />"+markers[i].getAttribute('zip')+" "+markers[i].getAttribute('town')+"<br />"+markers[i].getAttribute('country')+"<br /><br />"
            + markers[i].getAttribute('phone')+"<br />"+markers[i].getAttribute('fax')+"<br /><br /><a href='mailto:"+markers[i].getAttribute('email')+"'>"+markers[i].getAttribute('email')+"</a><br /><a href='http://"+markers[i].getAttribute('url')+"'>"+markers[i].getAttribute('url')+"</a>";
            var distance = parseFloat(markers[i].getAttribute('distance'));
            var point = new GLatLng(parseFloat(markers[i].getAttribute('latitude')),
                parseFloat(markers[i].getAttribute('longtitude')));

            var marker = createMarker(point, name, address);
            map.addOverlay(marker);
            var sidebarEntry = createSidebarEntry(marker, name, address, distance);
            sidebar.appendChild(sidebarEntry);
            bounds.extend(point);
        }
        map.setCenter(bounds.getCenter(), map.getBoundsZoomLevel(bounds));
    });
}

function createMarker(point, name, address, file) {
    // custom marker icons
    var icon = new GIcon();  
    icon.image = markerFile;
    icon.shadow = shadowFile;
    //icon.shadow = "http://www.umf-bikes.com/public/images/shadow_umf_map.png";
    icon.iconSize = new GSize(16, 24);
    icon.shadowSize = new GSize(22, 27);
    icon.iconAnchor = new GPoint(6, 20);
    icon.infoWindowAnchor = new GPoint(5, 1);
    //alert ("icon "+icon);
    var marker = new GMarker(point, icon);
    var html = '<div class="Marker"><b>' + name + '</b> <br/>' + address + '</div>';
    GEvent.addListener(marker, 'click', function() {
        marker.openInfoWindowHtml(html);
    });
    return marker;
}

function createSidebarEntry(marker, name, address, distance) {
    var div = document.createElement('div');
    var html = '<b>' + name + '</b> (' + distance.toFixed(1) + ')<br/>' + address;
    div.innerHTML = html;
    div.className = 'sidebarItem';
    //div.style.cursor = 'pointer';
    //div.style.marginBottom = '5px';
    GEvent.addDomListener(div, 'click', function() {
        GEvent.trigger(marker, 'click');
    });
    GEvent.addDomListener(div, 'mouseover', function() {
        //div.style.backgroundColor = '#666';
        div.className = 'sidebarMouseOver';
    });
    GEvent.addDomListener(div, 'mouseout', function() {
        //div.style.backgroundColor = '#333';
         div.className = 'sidebarMouseOut';
    });
    return div;
}
