'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var UserAutosuggest = (function (_React$Component) {
  _inherits(UserAutosuggest, _React$Component);

  function UserAutosuggest(props) {
    _classCallCheck(this, UserAutosuggest);

    var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(UserAutosuggest).call(this));

    _this.active = false;
    _this.selector = props.data.selector;
    _this.listOfUsers = props.data.users;
    _this.limit = 10;
    _this.selected = 1;
    return _this;
  }

  _createClass(UserAutosuggest, [{
    key: 'render',
    value: function render() {
      var query = '';
      if (typeof this.props.data.query != 'undefined' && this.props.data.query != null) {
        query = this.props.data.query.substring(1);
        query = query.replace(/\./, '\\.');
      }
      var styles = {
        'position': 'absolute',
        'top': this.props.data.selector.outerHeight(),
        'left': '0',
        'zIndex': 10,
        'width': '228px',
        'height': 'auto',
        'backgroundColor': '#f9f9f9',
        'boxShadow': '0 0 2px rgba(89, 89, 89, 0.75)',
        'display': 'none'
      };
      if (typeof this.props.data.active != 'undefined' && this.props.data.active != null) {
        this.active = this.props.data.active;
      }
      if (typeof this.props.data.selected != 'undefined' && this.props.data.selected != null) {
        this.selected = this.props.data.selected;
      }
      if (typeof this.props.data.changeSelected != 'undefined' && this.props.data.changeSelected != null) {
        var minUsersNum = 1;
        var maxUsersNum = this.listOfUsers.length;
        this.selected += this.props.data.changeSelected;
        if (this.selected < minUsersNum) {
          this.selected = minUsersNum;
        }
        if (this.selected > maxUsersNum) {
          this.selected = maxUsersNum;
        }
      }
      if (this.active) {
        styles['display'] = 'block';
      }
      var counter = 1;
      this.selector = this.props.data.selector;
      return React.createElement(
        'div',
        { className: 'userAutosuggest', style: styles },
        React.createElement(
          'ul',
          null,
          this.listOfUsers.map((function (object) {
            object.selector = this.selector;
            var regExp = new RegExp(query, 'ig');
            var showItem = true;
            if (query.length && !object.name.match(regExp)) {
              showItem = false;
            }
            if (counter > this.limit) {
              showItem = false;
            }
            object.selected = false;
            if (this.selected == counter) {
              object.selected = true;
            }
            object.parentProps = this.props.data;
            object.currentNum = counter;
            counter++;
            if (showItem) {
              return React.createElement(UserAutosuggestPerson, { key: object.id, data: object });
            }
          }).bind(this).bind(counter))
        )
      );
    }
  }]);

  return UserAutosuggest;
})(React.Component);

var UserAutosuggestPerson = (function (_React$Component2) {
  _inherits(UserAutosuggestPerson, _React$Component2);

  function UserAutosuggestPerson(props) {
    _classCallCheck(this, UserAutosuggestPerson);

    var _this2 = _possibleConstructorReturn(this, Object.getPrototypeOf(UserAutosuggestPerson).call(this));

    _this2.selected = props.data.selected;
    return _this2;
  }

  _createClass(UserAutosuggestPerson, [{
    key: 'onMouseOver',
    value: function onMouseOver() {
      var props = {
        'active': true,
        'selector': this.props.data.parentProps.selector,
        'query': this.props.data.parentProps.query,
        'selected': this.props.data.currentNum
      };
      CommentsActions.userNeedUserAutosuggest(props);
    }
  }, {
    key: 'onMouseOut',
    value: function onMouseOut() {}
  }, {
    key: 'onClick',
    value: function onClick(e) {
      e.preventDefault();
      CommentsActions.selectPersonForComment(this.props.data);
    }
  }, {
    key: 'render',
    value: function render() {
      var liStyle = {
        'paddingLeft': '9px',
        'paddingTop': '4px',
        'paddingBottom': '4px',
        'backgroundColor': '#f9f9f9'
      };
      var imgStyle = {
        'marginRight': '5px',
        'width': '41px',
        'height': '41px',
        'float': 'none'
      };
      var spanStyle = {
        'fontFamily': 'Arial',
        'fontSize': '12px',
        'fontWeight': 700,
        'lineHeight': '19px',
        'display': 'inline-block',
        'marginTop': '4px'
      };
      var object = this.props.data;
      this.selected = object.selected;

      var linkStyle = {
        'display': 'block',
        'color': '#666'
      };
      if (this.selected) {
        liStyle['backgroundColor'] = '#787a8c';
        linkStyle['color'] = '#fff';
      }
      return React.createElement(
        'li',
        {
          'data-selected': this.selected,
          style: liStyle,
          onClick: this.onClick.bind(this).bind(object),
          onMouseEnter: this.onMouseOver.bind(this),
          onMouseLeave: this.onMouseOut.bind(this) },
        React.createElement(
          'a',
          { href: '#', style: linkStyle },
          React.createElement('img', { alt: object.name, src: object.avatar, style: imgStyle }),
          ' ',
          React.createElement(
            'span',
            { style: spanStyle },
            object.name
          )
        )
      );
    }
  }]);

  return UserAutosuggestPerson;
})(React.Component);