var loader = new function () {

    /* Thực hiện thao tác rút gọn text cho các thẻ dùng class 'ddd' */
    this.dotdotdot = function(e) {
        if (e == undefined)
            e = {};
        if (e.className == undefined)
            e.className = "ddd";

        $("." + e.className).each(function (i, x) {

            var lh = $(x).css("line-height");
            var fz = parseFloat($(x).css("font-size"));
            if (lh === "normal") {
                var fw = $(x).css("font-weight");
                if (fw === "normal" || fw === "null")
                    lh = 1.2 * fz;
                else
                    lh = 1.4 * fz;
            } else {
                lh = parseFloat(lh);
                if (lh < 3)
                    lh = lh * fz;
            }
            var h = $(x).data("ddd-height");
            if (h != undefined) {
                $(x).dotdotdot({
                    height: h,
                    after: "a.read-more",
                });
                return;
            }

            var l = $(x).data("ddd-line");
            if (l != undefined) {
                $(x).dotdotdot({
                    height: l * lh,
                    after: "a.read-more"
                });
                return;
            }

            $(x).dotdotdot();
        });
    }
}

var helper = new function()
{
    this.defaultValue = function (variable, value) {
        if (variable === undefined)
            return value;
        return variable;
    }

    this.query = function()
    {
        this._data = [];

        this.ReadQuery = function () {
            this._data = [];

            var a = decodeURIComponent(document.location.search).substring(1).split('&');
            for (var i = 0; i < a.length; i++) {
                var it = a[i].split('=');

                if (it[0]) {
                    this._data.push({
                        key: it[0],
                        value: it[1],
                    });
                }
            }
        };

        this.ToUrl = function () {
            var r = "";

            for (var i = 0; i < this._data.length; i++) {
                r += "&" + this._data[i].key + "=" + encodeURIComponent(this._data[i].value);
            }

            return "?" + r.substring(1);
        };

        this.Data = function (key, value) {
            var index = -1;
            for (var i = 0; i < this._data.length; i++) {
                if (this._data[i].key.toLowerCase() == key.toLowerCase()) {
                    index = i;
                    break;
                }
            }

            if (value == undefined) {
                if (index == -1)
                    return undefined;
                return this._data[index].value;
            } else {
                if (index == -1)
                    this._data.push({ key: key, value: value });
                else
                    this._data[index].value = value;
            }
        };

        this.Remove = function (key) {
            var index = -1;
            for (var i = 0; i < this._data.length; i++) {
                if (this._data[i].key.toLowerCase() == key.toLowerCase()) {
                    index = i;
                    break;
                }
            }

            if (index == -1)
                return;
            this._data.splice(index, 1);
        };
    };

    this.clearVnString =  function(str) {
        var strRs = (str);
        strRs = strRs.toLowerCase();
        strRs = strRs.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
        strRs = strRs.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
        strRs = strRs.replace(/ì|í|ị|ỉ|ĩ/g, "i");
        strRs = strRs.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
        strRs = strRs.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
        strRs = strRs.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
        strRs = strRs.replace(/đ/g, "d");
        strRs = strRs.replace(/!|@|\$|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\'| |\"|\&|\#|\[|\]|~/g, "-");
        strRs = strRs.replace(/-+-/g, "-"); //thay thế 2- thành 1-
        strRs = strRs.replace(/^\-+|\-+$/g, "");//cắt bỏ ký tự - ở đầu và cuối chuỗi
        return strRs;
    }

    this.formatNumber = function(n) {
        if (n != null) {
            var rs = n.isNaN ? "00" : n;
            try {
                if (n < 10) {
                    rs = "0" + n;
                }
                if (n >= Math.pow(10, 3)) {
                    rs = $.formatNumber(n / Math.pow(10, 3), { format: "#.#", locale: "vn" }) + "k";
                }
                if (n >= Math.pow(10, 6)) {
                    rs = $.formatNumber(n / Math.pow(10, 3), { format: "#.#", locale: "vn" }) + "m";
                }
            } catch (e) {
                rs = "NaN";
            }
        } else {
            rs = "00";
        }
        return rs;
    }

    this.fixPhotolink = function(link, size) {
        if (size != null && link !== "")
            switch (size) {
                case 80:
                    link = link.replace("/999x/", "/080x/");
                    link = link.replace("/640x/", "/080x/");
                    link = link.replace("/320x/", "/080x/");
                    link = link.replace("/160x/", "/080x/");
                    break;
                case 160:
                    link = link.replace("/999x/", "/160x/");
                    link = link.replace("/640x/", "/160x/");
                    link = link.replace("/320x/", "/160x/");
                    link = link.replace("/080x/", "/160x/");
                    break;
                case 320:
                    link = link.replace("/999x/", "/320x/");
                    link = link.replace("/640x/", "/320x/");
                    link = link.replace("/160x/", "/320x/");
                    link = link.replace("/080x/", "/320x/");
                    break;
                case 640:
                    link = link.replace("/999x/", "/640x/");
                    link = link.replace("/320x/", "/640x/");
                    link = link.replace("/160x/", "/640x/");
                    link = link.replace("/080x/", "/640x/");
                    break;
                case 999:
                    link = link.replace("/640x/", "/999x/");
                    link = link.replace("/320x/", "/999x/");
                    link = link.replace("/160x/", "/999x/");
                    link = link.replace("/080x/", "/999x/");
                    break;
                default:
                    break;
            }
        return link;
    }
}
$(document).ready(function () {
    setTimeout(function () {
        loader.dotdotdot();
    }, 300);

    $(".star").raty({
        score: 2,
        path: 'js/raty-master/lib/images/',
    });
    $('list_cate li:odd').css('border-right:none');
    $('.footer .list a').click(function(event) {
        /* Act on the event */
        $(this).next().toggle();
    });

    $(".list-main-sub-product-compare .tem-compare").hover(function(){
        $(".list-main-sub-product-compare .tem-compare").removeClass("hover-bg");
        $(this).addClass("hover-bg");
    });

    $(".list-main-sub-product-filter .list > a").click(function(event){
        if($(this).hasClass("active") == true){
            $(this).removeClass("active");
            $(this).parent().next(".list-item").hide();

        }else{
            $(".list-main-sub-product-filter .list > a").removeClass("active");
            $(".list-main-sub-product-filter .list-item").hide();
            $(this).addClass("active");
            $(this).parent().next(".list-item").show();
            

        }
    });
});