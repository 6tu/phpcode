/*
 * JavaScript MD5 Demo JS
 * https://github.com/blueimp/JavaScript-MD5
 *
 * Copyright 2013, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 */

/* global md5 */

;(function () {
  'use strict'

  var input = document.getElementById('input')
  document.getElementById('calculate').addEventListener(
    'click',
    function (event) {
      event.preventDefault()
      document.getElementById('result').value = " \r\n小写:  " + md5(input.value).toLowerCase() + " \r\n大写:  " + md5(input.value).toUpperCase()
    }
  )
  input.value = '输入内容'
}())
