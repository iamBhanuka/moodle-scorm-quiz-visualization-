var sorted = [];

scormData.forEach((e) => {
  var c = containRecord(sorted, e);
  if (c) {
    var maxMark = Math.max(c.mark, e.mark);
    if (maxMark != c.mark) {
      c.mark = maxMark;
      c.attempt = e.attempt;
    }
    sorted = sorted.filter((d) => {
      return !(d && d.userid === c.userid && c.scorm_id === c.scorm_id);
    });
    sorted.push(c);
  } else {
    sorted.push(e);
  }
});

function scormSelect() {
    var x = document.getElementById("dd_scorm").value;
    var scormDataDiv = document.getElementById("scorm_data");
    scormDataDiv.innerHTML = "";
  
    sorted
      .filter((data) => {
        return data.scorm_id == x;
      })
      .forEach((data) => {
        scormDataDiv.innerHTML +=
          "id: " +
          data.userid +
          " name: " +
          data.firstname +
          " " +
          data.lastname +
          " score: " +
          data.mark +
          " attempt: " +
          data.attempt +
          "</br>";
      });
  }

  function containRecord(arr, e) {
    var c = arr.filter((x) => {
      return x && x.userid === e.userid && x.scorm_id === e.scorm_id;
    });
    if (c.length > 0) {
      return c[0];
    } else {
      null;
    }
  }
  