function NumPressed(a) {
    if (FlagNewNum) {
        FKeyPad.ReadOut.value = a;
        FlagNewNum = false;
    } else {
        if (FKeyPad.ReadOut.value == "0") {
            FKeyPad.ReadOut.value = a;
        } else {
            FKeyPad.ReadOut.value += a;
        }
    }
}

function Operation(b) {
    var a = FKeyPad.ReadOut.value;
    if (FlagNewNum && PendingOp != "=") {
    } else {
        FlagNewNum = true;
        if ("+" == PendingOp) {
            Accumulate += parseFloat(a);
        } else {
            if ("-" == PendingOp) {
                Accumulate -= parseFloat(a);
            } else {
                if ("/" == PendingOp) {
                    Accumulate /= parseFloat(a);
                } else {
                    if ("*" == PendingOp) {
                        Accumulate *= parseFloat(a);
                    } else {
                        Accumulate = parseFloat(a);
                    }
                }
            }
        }
        FKeyPad.ReadOut.value = Accumulate;
        PendingOp = b;
    }
}
function Decimal() {
    var a = FKeyPad.ReadOut.value;
    if (FlagNewNum) {
        a = "0.";
        FlagNewNum = false;
    } else {
        if (a.indexOf(".") == -1) {
            a += ".";
        }
    }
    FKeyPad.ReadOut.value = a;
}
function ClearEntry() {
    FKeyPad.ReadOut.value = "0";
    FlagNewNum = true;
}
function Clear() {
    Accumulate = 0;
    PendingOp = "";
    ClearEntry();
}
function Neg() {
    FKeyPad.ReadOut.value = parseFloat(FKeyPad.ReadOut.value) * -1;
}
function Percent() {
    FKeyPad.ReadOut.value = parseFloat(FKeyPad.ReadOut.value) / 100 * parseFloat(Accumulate);
}
function isNumberKey(b) {
    var a = b.which ? b.which : event.keyCode;
    if (a != 46 && a > 31 && (a < 48 || a > 57)) {
        return false;
    }
    return true;
}
var FKeyPad = document.Keypad;
var Accumulate = 0;
var FlagNewNum = false;
var PendingOp = "";
$(document).ready(function() {
    $("#calculator").draggable();
});