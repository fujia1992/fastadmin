const formatTime = date => {
  const year = date.getFullYear()
  const month = date.getMonth() + 1
  const day = date.getDate()
  const hour = date.getHours()
  const minute = date.getMinutes()
  const second = date.getSeconds()

  return [year, month, day].map(formatNumber).join('/') + ' ' + [hour, minute, second].map(formatNumber).join(':')
}

const formatNumber = n => {
  n = n.toString()
  return n[1] ? n : '0' + n
}

// 时间戳格式化成日期
function getTime(timestamp) {
  var time = arguments[0] || 0;
  var t, y, m, d, h, i, s;
  //t = time ? new Date(time) : new Date();
  t = time ? new Date(time * 1000) : new Date();
  y = t.getFullYear();    // 年
  m = t.getMonth() + 1;   // 月
  d = t.getDate();        // 日

  h = t.getHours();       // 时
  i = t.getMinutes();     // 分
  s = t.getSeconds();     // 秒

  return [y, m, d].map(formatNumber).join('-') + ' ' + [h, i, s].map(formatNumber).join(':');
}

function isEmptyObject(e) {
  var t;
  for (t in e)
    return !1;
  return !0;
}

module.exports = {
  formatTime: formatTime,
  getTime:getTime,
  isEmptyObject: isEmptyObject
}
