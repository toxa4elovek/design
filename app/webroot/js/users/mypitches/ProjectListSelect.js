'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ProjectListSelect = function (_React$Component) {
  _inherits(ProjectListSelect, _React$Component);

  function ProjectListSelect() {
    _classCallCheck(this, ProjectListSelect);

    return _possibleConstructorReturn(this, Object.getPrototypeOf(ProjectListSelect).apply(this, arguments));
  }

  _createClass(ProjectListSelect, [{
    key: 'onClick',
    value: function onClick(event) {
      event.preventDefault();
      ManagersControlPanelActions.sendAction('switchProjectList');
    }
  }, {
    key: 'render',
    value: function render() {
      console.log(this.props.state);
      var listContainerStyle = ProjectListSelectStyles.currentProjectsList;
      var imageUrl = '/img/filter-arrow-up.png';
      if (this.props.state.get('isProjectListOpened') === false) {
        listContainerStyle = ProjectListSelectStyles.currentProjectsListHidden;
        imageUrl = '/img/filter-arrow-down.png';
      }
      console.log(ProjectListSelectStyles);
      return React.createElement(
        'div',
        { style: ProjectListSelectStyles.projectSelectContainer },
        React.createElement('input', { style: ProjectListSelectStyles.projectInput, type: 'text', placeholder: 'выберите проект' }),
        React.createElement(
          'a',
          {
            onClick: this.onClick,
            style: ProjectListSelectStyles.arrowContainer,
            href: '#' },
          React.createElement('img', { alt: '', src: imageUrl, style: { paddingTop: '4px', marginRight: '1px' } })
        ),
        React.createElement(
          'div',
          { style: listContainerStyle },
          React.createElement(
            'ul',
            null,
            React.createElement(
              'li',
              { style: ProjectListSelectStyles.currentProjectsListItem },
              '1. Требуется разработка логотипа и написание названи'
            ),
            React.createElement(
              'li',
              { style: ProjectListSelectStyles.currentProjectsListItem },
              '2. Требуется разработка логотипа и написание названи'
            ),
            React.createElement(
              'li',
              { style: ProjectListSelectStyles.currentProjectsListItemWithHover },
              '3. Требуется разработка логотипа и написание названи'
            ),
            React.createElement(
              'li',
              { style: ProjectListSelectStyles.currentProjectsListItem },
              '4. Требуется разработка логотипа и написание названи'
            )
          )
        )
      );
    }
  }]);

  return ProjectListSelect;
}(React.Component);

var ProjectListSelectStyles = {
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
  currentProjectsListHidden: {
    display: 'none'
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