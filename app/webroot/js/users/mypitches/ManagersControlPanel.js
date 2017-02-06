'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ManagersControlPanel = function (_React$Component) {
  _inherits(ManagersControlPanel, _React$Component);

  function ManagersControlPanel() {
    _classCallCheck(this, ManagersControlPanel);

    return _possibleConstructorReturn(this, Object.getPrototypeOf(ManagersControlPanel).apply(this, arguments));
  }

  _createClass(ManagersControlPanel, [{
    key: 'render',
    value: function render() {
      console.log(this.props.state);
      return React.createElement(
        'div',
        { style: { marginBottom: '30px' } },
        React.createElement(
          'h3',
          { style: styles.title },
          'Добавить менеджера'
        ),
        React.createElement(
          'div',
          { style: styles.rowContainer },
          React.createElement('input', { style: styles.nameInput, type: 'text', placeholder: 'имя' }),
          React.createElement('input', { style: styles.emailInput, type: 'email', placeholder: 'name@domain.com' }),
          React.createElement(ProjectListSelect, { state: this.props.state })
        ),
        React.createElement('div', { className: 'clear' }),
        React.createElement('input', { type: 'button', value: '+ Добавить ещё', style: styles.addManagerButton }),
        React.createElement(
          'a',
          { className: 'button third', style: styles.inviteButton },
          'ПРИГЛАСИТЬ 1 ЧЕЛОВЕКА'
        ),
        React.createElement('img', { src: '/img/users/mypitches/hr.png', alt: '', style: styles.hr })
      );
    }
  }]);

  return ManagersControlPanel;
}(React.Component);

var styles = {
  title: {
    color: '#666666',
    height: '20px',
    fontFamily: 'OfficinaSansC Bold',
    fontSize: 20,
    width: '100%',
    textAlign: 'center',
    textTransform: 'none',
    marginBottom: '15px'
  },
  rowContainer: {
    width: '832px',
    marginLeft: '60px'
  },
  nameInput: {
    width: '230px',
    height: '44px',
    boxShadow: 'inset 1px 1px 1px rgba(0, 0, 0, 0.19)',
    backgroundColor: '#ffffff',
    float: 'left',
    marginRight: '16px',
    fontFamily: 'OfficinaSansC Book',
    fontSize: '17px'
  },
  emailInput: {
    width: '250px',
    height: '44px',
    boxShadow: 'inset 1px 1px 1px rgba(0, 0, 0, 0.19)',
    backgroundColor: '#ffffff',
    float: 'left',
    marginRight: '16px',
    fontFamily: 'OfficinaSansC Book',
    fontSize: '17px'
  },
  projectSelectContainer: {
    width: '279px',
    height: '44px',
    position: 'relative',
    float: 'left',
    backgroundColor: '#fff',
    marginTop: '5px'
  },
  projectInput: {
    width: '234px',
    height: '34px',
    boxShadow: 'none',
    backgroundColor: '#ffffff',
    float: 'left',
    fontFamily: 'OfficinaSansC Book',
    fontSize: '17px'
  },
  arrowContainer: {
    position: 'absolute',
    top: '6px',
    right: 0
  },
  addManagerButton: {
    color: '#64a06b',
    fontFamily: 'OfficinaSansC Bold',
    fontSize: '16px',
    fontWeight: '400',
    lineHeight: '30px',
    width: '832px',
    height: '43px',
    border: '1px solid #dbdbdb',
    backgroundColor: '#f2f2f2',
    marginLeft: '60px',
    textAlign: 'left'
  },
  inviteButton: {
    border: 0,
    borderRadius: 0,
    width: '772px',
    height: '43px',
    display: 'block',
    marginLeft: '60px',
    marginTop: '12px',
    lineHeight: '44px'
  },
  hr: {
    display: 'block',
    marginLeft: '60px',
    marginTop: '29px'
  },
  currentProjectsList: {
    position: 'absolute',
    width: '279px',
    boxShadow: '0 1px 3px rgba(0, 0, 0, 0.1)',
    borderRadius: '1px',
    border: '1px solid #d4d4d4',
    backgroundColor: '#ffffff',
    top: '50px',
    padding: '0'
  },
  currentProjectsListItem: {
    padding: '9px 9px 9px 9px',
    textTransform: 'none'
  },
  currentProjectsListItemWithHover: {
    padding: '9px 9px 9px 9px',
    textTransform: 'none',
    backgroundColor: '#ecf8fc'
  }
};