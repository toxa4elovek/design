// jQuery Placeholder Enhanced by Denis Ciccale (dciccale@gmail.com), modified by Aleks Hudochenkov || Dual licensed under the MIT and GPL licenses
(function (Jquery) {
    Jquery.fn.placeholderEnhanced = function () {
        if (!this.length) {
            return
        }
        var e = "placeholder",
            d = e in document.createElement("input");
        //if (Jquery.browser.opera || Jquery.browser.safari) {
            d = false
        //}
        if (!d) {
            Jquery("form").submit(function () {
                var f = Jquery(this);
                f.find("input[placeholder], textarea[placeholder]").each(function () {
                    var g = Jquery(this);
                    if (g.attr("value") === g.attr("placeholder")) {
                        g.val("")
                    }
                })
            })
        }
        return this.each(function () {
            var k = Jquery(this),
                l = k.attr("placeholder"),
                m = k.attr("type") === "password";
            var i = function () {
                if (k.hasClass(e)) {
                    if (!d) {
                        k.val("");
                        k.attr('placeholder', '')
                    }
                    k.removeClass(e)
                }
            };
            var g = function (p) {
                if (!k.val() || k.val() === l) {
                    if (!d) {
                        if (!m) {
                            k.addClass(e).val(l)
                        } else {
                            j(f);
                            n(k)
                        }
                    } else {
                        k.addClass(e)
                    }
                }
            };
            var n = function (p) {
                p.css({
                    position: "absolute",
                    left: "-9999em"
                })
            };
            var j = function (p) {
                return p.removeAttr("style")
            };
            if (!m || d) {
                k.bind("focus.placeholder", i)
            } else {
                var h = (k[0].className) ? " " + k[0].className : "",
                    o = (k[0].size) ? "size=" + k[0].size : "";
                var f = Jquery('<input type="text" class="' + e + h + '" value="' + l + '"' + o + ' tabindex="-1" />');
                f.bind("focus.placeholder", function () {
                    k.trigger("focus.placeholder")
                });
                k.before(f).bind("focus.placeholder", function () {
                    j(k);
                    k.attr('placeholder', '');
                    n(f)
                })
            }
            k.bind("blur.placeholder", g).trigger("blur.placeholder")
        })
    };
    Jquery(function () {
        Jquery("input[placeholder], textarea[placeholder]").placeholderEnhanced()
    });
    var c = "placeholder" in document.createElement("input");
    if (Jquery.browser.opera || Jquery.browser.safari) {
        c = false
    }
    if (!c) {
        var a = Jquery.fn.val;
        Jquery.fn.val = function (d) {
            if (!arguments.length) {
                return Jquery(this).attr("value") === Jquery(this).attr("placeholder") ? "" : Jquery(this).attr("value")
            }
            return a.call(this, d)
        }
    }
})(jQuery);