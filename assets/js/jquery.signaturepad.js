!(function (F) {
  function n(e, t) {
    var u = this,
      b = F.extend({}, F.fn.signaturePad.defaults, t),
      r = F(e),
      p = F(b.canvas, r),
      o = p.get(0),
      d = null,
      c = { x: null, y: null },
      h = [],
      f = !1,
      n = !1,
      a = !1,
      l = !1,
      s = 30,
      g = s,
      v = 0,
      m = [];
    function y() {
      clearTimeout(f), (n = f = !1);
    }
    function C(e, t) {
      var n, a, r;
      if (
        (e.preventDefault(),
        (n = F(e.target).offset()),
        clearTimeout(f),
        (f = !1),
        (r =
          void 0 !== e.changedTouches
            ? ((a = Math.floor(e.changedTouches[0].pageX - n.left)),
              Math.floor(e.changedTouches[0].pageY - n.top))
            : ((a = Math.floor(e.pageX - n.left)),
              Math.floor(e.pageY - n.top))),
        c.x === a && c.y === r)
      )
        return !0;
      if (
        (null === c.x && (c.x = a),
        null === c.y && (c.y = r),
        t && (r += t),
        d.beginPath(),
        d.moveTo(c.x, c.y),
        d.lineTo(a, r),
        (d.lineCap = b.penCap),
        d.stroke(),
        d.closePath(),
        !0 === b.drawBezierCurves)
      ) {
        m.push({ lx: a, ly: r, mx: c.x, my: c.y });
        var o = 4 * b.bezierSkip;
        if (m.length >= o) {
          var l = h.slice(h.length - o + 2, h.length);
          for (i in ((d.strokeStyle = b.bgColour), l)) {
            var s = l[i];
            d.beginPath(),
              d.moveTo(s.mx, s.my),
              d.lineTo(s.lx, s.ly),
              (d.lineCap = b.penCap),
              d.stroke(),
              d.closePath();
          }
          (d.strokeStyle = b.penColour), O(m, d), (m = m.slice(o - 1, o));
        }
      }
      h.push({ lx: a, ly: r, mx: c.x, my: c.y }),
        (c.x = a),
        (c.y = r),
        b.onDraw && "function" == typeof b.onDraw && b.onDraw.apply(u);
    }
    function w(e) {
      e && "touchend" !== e.type && "touchcancel" != e.type
        ? C(e, 1)
        : (a
            ? p.each(function () {
                this.removeEventListener("touchmove", C);
              })
            : p.unbind("mousemove.signaturepad"),
          0 < h.length &&
            (b.onDrawEnd &&
              "function" == typeof b.onDrawEnd &&
              b.onDrawEnd.apply(u),
            (m = []),
            x(),
            z(h, d, !1))),
        (c.x = null),
        (c.y = null),
        b.output && 0 < h.length && F(b.output, r).val(JSON.stringify(h));
    }
    function x() {
      d.clearRect(0, 0, o.width, o.height),
        (d.fillStyle = b.bgColour),
        d.fillRect(0, 0, o.width, o.height),
        b.displayOnly ||
          (b.lineWidth &&
            (d.beginPath(),
            (d.lineWidth = b.lineWidth),
            (d.strokeStyle = b.lineColour),
            d.moveTo(b.lineMargin, b.lineTop),
            d.lineTo(o.width - b.lineMargin, b.lineTop),
            d.stroke(),
            d.closePath())),
        (d.lineWidth = b.penWidth),
        (d.strokeStyle = b.penColour);
    }
    function I() {
      x(), F(b.output, r).val(""), (h = []), w();
    }
    function D(e, t) {
      null == c.x ? C(e, 1) : C(e, t);
    }
    function M(e, t) {
      a
        ? t.addEventListener("touchmove", D, !1)
        : p.bind("mousemove.signaturepad", D),
        C(e, 1);
    }
    function S(e) {
      l ||
        ((l = !0),
        F("input").blur(),
        void 0 !== e.changedTouches && (a = !0),
        a
          ? (p.each(function () {
              this.addEventListener("touchend", w, !1),
                this.addEventListener("touchcancel", w, !1);
            }),
            p.unbind("mousedown.signaturepad"))
          : (F(document).bind("mouseup.signaturepad", function () {
              n && (w(), y());
            }),
            p.bind("mouseleave.signaturepad", function (e) {
              n && w(e),
                n &&
                  !f &&
                  (f = setTimeout(function () {
                    w(), y();
                  }, 500));
            }),
            p.each(function () {
              this.ontouchstart = null;
            })));
    }
    function P() {
      F(b.typed, r).hide(),
        I(),
        p.each(function () {
          this.ontouchstart = function (e) {
            e.preventDefault(), (n = !0), S(e), M(e, this);
          };
        }),
        p.bind("mousedown.signaturepad", function (e) {
          e.preventDefault(), (n = !0), S(e), M(e);
        }),
        F(b.clear, r).bind("click.signaturepad", function (e) {
          e.preventDefault(), I();
        }),
        F(b.typeIt, r).bind("click.signaturepad", function (e) {
          e.preventDefault(), T();
        }),
        F(b.drawIt, r).unbind("click.signaturepad"),
        F(b.drawIt, r).bind("click.signaturepad", function (e) {
          e.preventDefault();
        }),
        F(b.typeIt, r).removeClass(b.currentClass),
        F(b.drawIt, r).addClass(b.currentClass),
        F(b.sig, r).addClass(b.currentClass),
        F(b.typeItDesc, r).hide(),
        F(b.drawItDesc, r).show(),
        F(b.clear, r).show();
    }
    function T() {
      I(),
        (l = !1),
        p.each(function () {
          this.removeEventListener &&
            (this.removeEventListener("touchend", w),
            this.removeEventListener("touchcancel", w),
            this.removeEventListener("touchmove", C)),
            this.ontouchstart && (this.ontouchstart = null);
        }),
        F(document).unbind("mouseup.signaturepad"),
        p.unbind("mousedown.signaturepad"),
        p.unbind("mousemove.signaturepad"),
        p.unbind("mouseleave.signaturepad"),
        F(b.clear, r).unbind("click.signaturepad"),
        F(b.typed, r).show(),
        F(b.drawIt, r).bind("click.signaturepad", function (e) {
          e.preventDefault(), P();
        }),
        F(b.typeIt, r).unbind("click.signaturepad"),
        F(b.typeIt, r).bind("click.signaturepad", function (e) {
          e.preventDefault();
        }),
        F(b.output, r).val(""),
        F(b.drawIt, r).removeClass(b.currentClass),
        F(b.typeIt, r).addClass(b.currentClass),
        F(b.sig, r).removeClass(b.currentClass),
        F(b.drawItDesc, r).hide(),
        F(b.clear, r).hide(),
        F(b.typeItDesc, r).show(),
        (g = s = F(b.typed, r).css("font-size").replace(/px/, ""));
    }
    function E(e) {
      var t = F(b.typed, r),
        n = e.replace(/>/g, "&gt;").replace(/</g, "&lt;").trim(),
        a = v,
        i = 0.5 * g;
      if (((v = n.length), t.html(n), n)) {
        if (a < v && t.outerWidth() > o.width)
          for (; t.outerWidth() > o.width; ) g--, t.css("font-size", g + "px");
        if (v < a && t.outerWidth() + i < o.width && g < s)
          for (; t.outerWidth() + i < o.width && g < s; )
            g++, t.css("font-size", g + "px");
      } else t.css("font-size", s + "px");
    }
    function W() {
      var e = !0,
        t = { drawInvalid: !1, nameInvalid: !1 },
        n = [r, b],
        a = [t, r, b];
      return (
        b.onBeforeValidate && "function" == typeof b.onBeforeValidate
          ? b.onBeforeValidate.apply(u, n)
          : function (e, t) {
              F("p." + t.errorClass, e).remove(),
                e.removeClass(t.errorClass),
                F("input, label", e).removeClass(t.errorClass);
            }.apply(u, n),
        b.drawOnly && h.length < 1 && b.required && (e = !(t.drawInvalid = !0)),
        "" === F(b.name, r).val() && (e = !(t.nameInvalid = !0)),
        b.onFormError && "function" == typeof b.onFormError
          ? b.onFormError.apply(u, a)
          : function (e, t, n) {
              e.nameInvalid &&
                (t.prepend(
                  [
                    '<p class="',
                    n.errorClass,
                    '">',
                    n.errorMessage,
                    "</p>",
                  ].join("")
                ),
                F(n.name, t).focus(),
                F(n.name, t).addClass(n.errorClass),
                F("label[for=" + F(n.name).attr("id") + "]", t).addClass(
                  n.errorClass
                )),
                e.drawInvalid &&
                  t.prepend(
                    [
                      '<p class="',
                      n.errorClass,
                      '">',
                      n.errorMessageDraw,
                      "</p>",
                    ].join("")
                  );
            }.apply(u, a),
        e
      );
    }
    function O(e, t) {
      for (var n = [], a = [], i = 0; i < e.length - 1; i++)
        if ("object" == typeof e[i] && "object" == typeof e[i + 1]) {
          var r = e[i],
            o = e[i + 1];
          if (r.mx == r.lx && r.my == r.ly) continue;
          n.push(r),
            (r.lx == o.mx && r.ly == o.my) ||
              (r.mx == o.lx && r.my == o.ly) ||
              (a.push(n), (n = [])),
            i == e.length - 2 && (n.push(o), a.push(n));
        }
      var l = [];
      for (k = 0; k < a.length; k++) {
        var s = a[k].pop();
        (a[k] = a[k].filter(function (e, t) {
          return t % b.bezierSkip == 0;
        })),
          a[k].push(s);
        n = a[k];
        for (j = 0; j < n.length; j++) {
          var u = n[j],
            d = Math.abs(u.lx - u.mx) + Math.abs(u.ly - u.my);
          l.push(d);
        }
      }
      var c = stats(l);
      for (
        c.length = numeric.sum(l), c.mean *= 3, c.deviation *= 3, k = 0;
        k < a.length;
        k++
      ) {
        var p = (n = a[k]).map(function (e) {
            return [e.lx, e.ly];
          }),
          h = getBezierControlPoints(p);
        for (var i in h) {
          var f,
            g,
            v = h[i][0],
            m = h[i][1],
            y = h[i][2],
            C = h[i][3];
          !0 === b.variableStrokeWidth &&
            (0 <
            (f =
              (Math.abs(v[0] - m[0]) +
                Math.abs(m[0] - y[0]) +
                Math.abs(y[0] - C[0]) +
                Math.abs(v[1] - m[1]) +
                Math.abs(m[1] - y[1]) +
                Math.abs(y[1] - C[1]) -
                c.mean) /
              c.deviation)
              ? (g = 3 - f / 2.5)
              : f <= 0 && (g = 3 - 2 * f)),
            t.beginPath(),
            t.moveTo(v[0], v[1]),
            t.bezierCurveTo(m[0], m[1], y[0], y[1], C[0], C[1]),
            (t.lineWidth = b.penWidth),
            (t.lineWidth = g),
            (t.lineCap = b.penCap),
            t.stroke(),
            t.closePath();
        }
      }
    }
    function z(e, t, n) {
      var a, i, r, o, l, s, u, d;
      for (var c in (b.autoscale
        ? ((i = a = 0),
          (r = F(p).width()),
          (o = F(p).height()),
          F.each(e, function (e, t) {
            (a = Math.max(t.mx, t.lx, a)),
              (r = Math.min(t.mx, t.lx, r)),
              (i = Math.max(t.my, t.ly, i)),
              (o = Math.min(t.my, t.ly, o));
          }),
          (u =
            (l = (a *= 1.15) - (r *= 0.85)) / (s = (i *= 1.15) - (o *= 0.85))),
          (d = p.width() / p.height() < u ? p.width() / l : p.height() / s),
          t.translate(-r * d, -o * d),
          t.scale.apply(t, [d, d]),
          t.translate((p.width() / d - l) / 2, (p.height() / d - s) / 2))
        : t.scale.apply(t, b.scale),
      e))
        "object" == typeof e[c] &&
          (!1 === b.drawBezierCurves &&
            (t.beginPath(),
            t.moveTo(e[c].mx, e[c].my),
            t.lineTo(e[c].lx, e[c].ly),
            (t.lineCap = b.penCap),
            t.stroke(),
            t.closePath()),
          n && h.push({ lx: e[c].lx, ly: e[c].ly, mx: e[c].mx, my: e[c].my }));
      !0 === b.drawBezierCurves && O(e, t);
    }
    F.extend(u, {
      init: function () {
        parseFloat(
          (/CPU.+OS ([0-9_]{3}).*AppleWebkit.*Mobile/i.exec(
            navigator.userAgent
          ) || [0, "4_2"])[1].replace("_", ".")
        ) < 4.1 &&
          ((F.fn.Oldoffset = F.fn.offset),
          (F.fn.offset = function () {
            var e = F(this).Oldoffset();
            return (e.top -= window.scrollY), (e.left -= window.scrollX), e;
          })),
          F(b.typed, r).bind("selectstart.signaturepad", function (e) {
            return F(e.target).is(":input");
          }),
          p.bind("selectstart.signaturepad", function (e) {
            return F(e.target).is(":input");
          }),
          !o.getContext && FlashCanvas && FlashCanvas.initElement(o),
          o.getContext &&
            ((d = o.getContext("2d")),
            F(b.sig, r).show(),
            b.displayOnly ||
              (b.drawOnly ||
                (F(b.name, r).bind("keyup.signaturepad", function () {
                  E(F(this).val());
                }),
                F(b.name, r).bind("blur.signaturepad", function () {
                  E(F(this).val());
                }),
                F(b.drawIt, r).bind("click.signaturepad", function (e) {
                  e.preventDefault(), P();
                })),
              (b.drawOnly || "drawIt" === b.defaultAction ? P : T)(),
              b.validateFields &&
                (F(e).is("form")
                  ? F(e).bind("submit.signaturepad", W)
                  : F(e).parents("form").bind("submit.signaturepad", W)),
              F(b.sigNav, r).show()));
      },
      updateOptions: function (e) {
        F.extend(b, e);
      },
      regenerate: function (e) {
        u.clearCanvas(),
          F(b.typed, r).hide(),
          "string" == typeof e && (e = JSON.parse(e)),
          z(e, d, !0),
          b.output &&
            0 < F(b.output, r).length &&
            F(b.output, r).val(JSON.stringify(h));
      },
      clearCanvas: function () {
        I();
      },
      getSignature: function () {
        return h;
      },
      getSignatureString: function () {
        return JSON.stringify(h);
      },
      getSignatureImage: function () {
        var e,
          t = document.createElement("canvas"),
          n = null;
        return (
          (t.style.position = "absolute"),
          (t.style.top = "-999em"),
          (t.width = o.width),
          (t.height = o.height),
          document.body.appendChild(t),
          !t.getContext && FlashCanvas && FlashCanvas.initElement(t),
          ((n = t.getContext("2d")).fillStyle = b.bgColour),
          n.fillRect(0, 0, o.width, o.height),
          (n.lineWidth = b.penWidth),
          (n.strokeStyle = b.penColour),
          z(h, n),
          (e = t.toDataURL.apply(t, arguments)),
          document.body.removeChild(t),
          (t = null),
          e
        );
      },
      validateForm: W,
    });
  }
  (F.fn.signaturePad = function (e) {
    var t = null;
    return (
      this.each(function () {
        F.data(this, "plugin-signaturePad")
          ? (t = F.data(this, "plugin-signaturePad")).updateOptions(e)
          : ((t = new n(this, e)).init(),
            F.data(this, "plugin-signaturePad", t));
      }),
      t
    );
  }),
    (F.fn.signaturePad.defaults = {
      defaultAction: "typeIt",
      displayOnly: !1,
      drawOnly: !1,
      canvas: "canvas",
      sig: ".sig",
      sigNav: ".sigNav",
      bgColour: "#ffffff",
      penColour: "#0a0909",
      penWidth: 2,
      penCap: "round",
      lineColour: "#ccc",
      lineWidth: 2,
      lineMargin: 5,
      lineTop: 35,
      name: ".name",
      typed: ".typed",
      clear: ".clearButton",
      typeIt: ".typeIt a",
      drawIt: ".drawIt a",
      typeItDesc: ".typeItDesc",
      drawItDesc: ".drawItDesc",
      output: ".output",
      currentClass: "current",
      validateFields: !0,
      errorClass: "error",
      errorMessage: "Please enter your name",
      errorMessageDraw: "Please sign the document",
      onBeforeValidate: null,
      onFormError: null,
      onDraw: null,
      onDrawEnd: null,
      scale: [1, 1],
      autoscale: !1,
      drawBezierCurves: !1,
      variableStrokeWidth: !1,
      bezierSkip: 4,
      required: !0,
    });
})(jQuery);
