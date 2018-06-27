var map = L.map('map',{
    center: [47.094109, 2.211040],
    zoom: 6
});

L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);
<<<<<<< HEAD
=======

L.control.locate().addTo(map);

>>>>>>> 968385a75e8fb9227e415437adb5206c19af9d88
