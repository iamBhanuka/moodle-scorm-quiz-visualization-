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