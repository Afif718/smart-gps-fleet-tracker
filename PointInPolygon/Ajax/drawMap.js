var mapOptions;
var map;

var coordinates = [];
let new_coordinates = [];
let lastElement;

function InitMap() {
  var location = new google.maps.LatLng(26.128133, 89.139163);
  mapOptions = {
    zoom: 12,
    center: location,
    mapTypeId: google.maps.MapTypeId.RoadMap,
  };
  map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
  var all_overlays = [];
  var selectedShape;
  var drawingManager = new google.maps.drawing.DrawingManager({
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: [google.maps.drawing.OverlayType.POLYGON],
    },
    polygonOptions: {
      clickable: true,
      draggable: false,
      editable: true,
      fillColor: "#ADFF2F",
      fillOpacity: 0.5,
    },
  });

  function clearSelection() {
    if (selectedShape) {
      selectedShape.setEditable(false);
      selectedShape = null;
    }
  }

  function setSelection(shape) {
    clearSelection();
    selectedShape = shape;
    shape.setEditable(true);
  }

  function deleteSelectedShape() {
    if (selectedShape) {
      selectedShape.setMap(null);
      drawingManager.setMap(map);
      coordinates.splice(0, coordinates.length);
      document.getElementById("info").innerHTML = "";
    }
  }

  function CenterControl(controlDiv, map) {
    var controlUI = document.createElement("div");
    controlUI.style.backgroundColor = "#fff";
    controlUI.style.border = "2px solid #fff";
    controlUI.style.borderRadius = "3px";
    controlUI.style.boxShadow = "0 2px 6px rgba(0,0,0,.3)";
    controlUI.style.cursor = "pointer";
    controlUI.style.marginBottom = "22px";
    controlUI.style.textAlign = "center";
    controlUI.title = "Select to delete the shape";
    controlDiv.appendChild(controlUI);

    var controlText = document.createElement("div");
    controlText.style.color = "rgb(25,25,25)";
    controlText.style.fontFamily = "Roboto,Arial,sans-serif";
    controlText.style.fontSize = "16px";
    controlText.style.lineHeight = "38px";
    controlText.style.paddingLeft = "5px";
    controlText.style.paddingRight = "5px";
    controlText.innerHTML = "Delete Selected Area";
    controlUI.appendChild(controlText);

    controlUI.addEventListener("click", function () {
      deleteSelectedShape();
    });
  }

  drawingManager.setMap(map);

  var getPolygonCoords = function (newShape) {
    coordinates.splice(0, coordinates.length);

    var len = newShape.getPath().getLength();

    for (var i = 0; i < len; i++) {
      var latLng = newShape.getPath().getAt(i);
      coordinates.push([latLng.lat(), latLng.lng()]);
    }
    document.getElementById("info").innerHTML = JSON.stringify(coordinates);
  };

  google.maps.event.addListener(
    drawingManager,
    "polygoncomplete",
    function (event) {
      event.getPath().getLength();
      google.maps.event.addListener(event, "dragend", getPolygonCoords(event));

      google.maps.event.addListener(event.getPath(), "insert_at", function () {
        getPolygonCoords(event);
      });

      google.maps.event.addListener(event.getPath(), "set_at", function () {
        getPolygonCoords(event);
      });
    }
  );

  google.maps.event.addListener(
    drawingManager,
    "overlaycomplete",
    function (event) {
      all_overlays.push(event);
      if (event.type !== google.maps.drawing.OverlayType.MARKER) {
        drawingManager.setDrawingMode(null);

        var newShape = event.overlay;
        newShape.type = event.type;
        google.maps.event.addListener(newShape, "click", function () {
          setSelection(newShape);
        });
        setSelection(newShape);
      }
    }
  );

  var centerControlDiv = document.createElement("div");
  var centerControl = new CenterControl(centerControlDiv, map);

  centerControlDiv.index = 1;
  map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(
    centerControlDiv
  );
}

InitMap();
