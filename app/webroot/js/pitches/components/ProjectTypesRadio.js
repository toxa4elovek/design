'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ProjectTypesRadio = (function (_React$Component) {
    _inherits(ProjectTypesRadio, _React$Component);

    function ProjectTypesRadio() {
        _classCallCheck(this, ProjectTypesRadio);

        return _possibleConstructorReturn(this, Object.getPrototypeOf(ProjectTypesRadio).apply(this, arguments));
    }

    _createClass(ProjectTypesRadio, [{
        key: 'onChange',
        value: function onChange(e) {
            var fileFormats = '';
            if (e.target.value === 'copyrighting') {
                fileFormats = '<li class="wide graysupplement"><label><input type="checkbox" name="" data-value="DOC" checked="">.DOC</label></li>\n                <li class="wide graysupplement"><label><input type="checkbox" name="" data-value="PDF" checked="">.PDF</label></li>\n                <li class="graysupplement"><label><input type="checkbox" name="" data-value="другие">другие</label></li>';
                $('.subscriber-qualities-title').text('Какие 3 качества нужно донести?');
                $('.subscriber-audience-title').text('Какими свойствами должен обладать копирайтинг?');
                $('.extensions').html(fileFormats);
            } else {
                fileFormats = '<li class="wide graysupplement"><label><input type="checkbox" name="" checked="" data-value="EPS">.EPS</label></li>\n                <li class="wide graysupplement"><label><input type="checkbox" name="" data-value="AI">.AI (Illustrator)</label></li>\n                <li class="graysupplement"><label><input type="checkbox" name="" data-value="JPG" checked="">.JPG</label></li>\n                <li class="graysupplement"><label><input type="checkbox" name="" data-value="PNG" checked="">.PNG</label></li>\n                <li class="graysupplement"><label><input type="checkbox" name="" data-value="PDF">.PDF</label></li>\n                <li class="wide graysupplement"><label><input type="checkbox" name="" data-value="PSD">.PSD (Photoshop)</label></li>\n                <li class="wide graysupplement"><label><input type="checkbox" name="" data-value="Indd">.Indd (In Design)</label></li>\n                <li class="graysupplement"><label><input type="checkbox" name="" data-value="GIF">.GIF</label></li>\n                <li class="graysupplement"><label><input type="checkbox" name="" data-value="TIFF">.TIFF</label></li>\n                <li class="graysupplement"><label><input type="checkbox" name="" data-value="другие">другие</label></li>';
                $('.subscriber-qualities-title').text('Какие 3 качества нужно донести через дизайн?');
                $('.subscriber-audience-title').text('Какими свойствами должен обладать ваш дизайн?');
                $('.extensions').html(fileFormats);
            }
        }
    }, {
        key: 'render',
        value: function render() {
            var info = this.props.data;
            var checked = false;
            if (info.checked) {
                checked = true;
            }
            var radioStyle = {
                "width": "24px",
                "height": "14px",
                "verticalAlign": "middle",
                "boxShadow": "none !important"
            };
            var input = React.createElement('input', { className: info.name,
                defaultChecked: checked,
                name: 'subscription-type',
                onChange: this.onChange.bind(this),
                ref: 'radioInput',
                style: radioStyle,
                type: 'radio',
                value: info.value
            });
            var labelStyle = {
                "textShadow": "-1px 0 0 #FFFFFF",
                "marginTop": "6px",
                "fontSize": "12px",
                "lineHeight": "16px",
                "color": "#757575"
            };
            return React.createElement(
                'div',
                { className: 'radio-container', style: { "float": "left", "marginLeft": 0, "width": "170px", "paddingTop": "5px" } },
                React.createElement(
                    'label',
                    { style: labelStyle },
                    input,
                    React.createElement(
                        'span',
                        null,
                        info.label
                    )
                ),
                React.createElement('input', { type: 'hidden', name: 'subscription-type', value: info.value })
            );
        }
    }]);

    return ProjectTypesRadio;
})(React.Component);