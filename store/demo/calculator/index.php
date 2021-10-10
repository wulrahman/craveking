<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.js"></script>
<link href="files/css/boilerplate.css" rel="stylesheet" type="text/css">
<link href="files/css/style.css" rel="stylesheet" type="text/css">
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<div id="calculator">
<FORM name="Keypad" action="">
<input name="ReadOut" onKeyPress="return isNumberKey(event)" class="c-input" type="Text" size=24 value="0"><input name="btnPercent" type="Button" class="c-button q-button" value="%" onClick="Percent()"><input name="btnNeg" type="Button" class="c-button q-button" value="+/-" onClick="Neg()"><input name="btnClearEntry" type="Button" class="c-button q-button" value="CE" onClick="ClearEntry()"><input name="btnClear" type="Button" class="c-button can-button" value="C" onClick="Clear()"><input name="btnOne" type="Button" class="c-button" value="1" onClick="NumPressed(1)"><input name="btnTwo" type="Button" class="c-button" value="2" onClick="NumPressed(2)"><input name="btnThree" type="Button" class="c-button" value="3" onClick="NumPressed(3)"><input name="btnDivide" type="Button" class="c-button q-button" value="/" onClick="Operation('/')"><input name="btnFour" type="Button" class="c-button" value="4" onClick="NumPressed(4)"><input name="btnFive" type="Button" class="c-button" value="5" onClick="NumPressed(5)"><input name="btnSix" type="Button" class="c-button" value="6" onClick="NumPressed(6)"><input name="btnMultiply q-button" type="Button" class="c-button q-button" value="*" onClick="Operation('*')"><input name="btnSeven" type="Button" class="c-button" value="7" onClick="NumPressed(7)"><input name="btnEight" type="Button" class="c-button" value="8" onClick="NumPressed(8)"><input name="btnNine" type="Button" class="c-button" value="9" onClick="NumPressed(9)"><input name="btnMinus" type="Button" class="c-button q-button" value="-" onClick="Operation('-')"><input name="btnZero" type="Button" class="c-button" value="0" onClick="NumPressed(0)"><input name="btnDecimal" type="Button" class="c-button" value="." onClick="Decimal()"><input name="btnEquals" type="Button" class="c-button equal-buttom" value="=" onClick="Operation('=')"><input name="btnPlus" type="Button" class="c-button q-button" value="+" onClick="Operation('+')">
</form>
</div>
<script src="files/js/script.js"></script>
<footer class="footer">Powered by <a href="https://www.cragglist.com/">Cragglist</a></footer>