class UserAutosuggest extends React.Component {
  constructor (props) {
    super()
    this.active = false
    this.selector = props.data.selector
    this.listOfUsers = props.data.users
    this.limit = 10
    this.selected = 1
  }
  render () {
    let query = ''
    if ((typeof (this.props.data.query) != 'undefined') && (this.props.data.query != null)) {
      query = this.props.data.query.substring(1)
      query = query.replace(/\./, '\\.')
    }
    const styles = {
      'position': 'absolute',
      'top': this.props.data.selector.outerHeight(),
      'left': '0',
      'zIndex': 10,
      'width': '228px',
      'height': 'auto',
      'backgroundColor': '#f9f9f9',
      'boxShadow': '0 0 2px rgba(89, 89, 89, 0.75)',
      'display': 'none'
    }
    if ((typeof (this.props.data.active) != 'undefined') && (this.props.data.active != null)) {
      this.active = this.props.data.active
    }
    if ((typeof (this.props.data.selected) != 'undefined') && (this.props.data.selected != null)) {
      this.selected = this.props.data.selected
    }
    if ((typeof (this.props.data.changeSelected) != 'undefined') && (this.props.data.changeSelected != null)) {
      const minUsersNum = 1
      const maxUsersNum = this.listOfUsers.length
      this.selected += this.props.data.changeSelected
      if (this.selected < minUsersNum) {
        this.selected = minUsersNum
      }
      if (this.selected > maxUsersNum) {
        this.selected = maxUsersNum
      }
    }
    if (this.active) {
      styles['display'] = 'block'
    }
    let counter = 1
    this.selector = this.props.data.selector
    return (
    <div className='userAutosuggest' style={styles}>
      <ul>
        {this.listOfUsers.map(function (object) {
          object.selector = this.selector
          const regExp = new RegExp(query, 'ig')
          let showItem = true
          if ((query.length) && (!object.name.match(regExp))) {
           showItem = false
          }
          if (counter > this.limit) {
           showItem = false
          }
          object.selected = false
          if (this.selected == counter) {
           object.selected = true
          }
          object.parentProps = this.props.data
          object.currentNum = counter
          counter++
          if (showItem) {
           return <UserAutosuggestPerson key={object.id} data={object} />
          }
        }.bind(this).bind(counter))}
      </ul>
    </div>
    )
  }
}

class UserAutosuggestPerson extends React.Component {
  constructor (props) {
    super()
    this.selected = props.data.selected
  }
  onMouseOver () {
    let props = {
      'active': true,
      'selector': this.props.data.parentProps.selector,
      'query': this.props.data.parentProps.query,
      'selected': this.props.data.currentNum
    }
    CommentsActions.userNeedUserAutosuggest(props)
  }
  onMouseOut () {}
  onClick (e) {
    e.preventDefault()
    CommentsActions.selectPersonForComment(this.props.data)
  }
  render () {
    const liStyle = {
      'paddingLeft': '9px',
      'paddingTop': '4px',
      'paddingBottom': '4px',
      'backgroundColor': '#f9f9f9'
    }
    const imgStyle = {
      'marginRight': '5px',
      'width': '41px',
      'height': '41px',
      'float': 'none'
    }
    const spanStyle = {
      'fontFamily': 'Arial',
      'fontSize': '12px',
      'fontWeight': 700,
      'lineHeight': '19px',
      'display': 'inline-block',
      'marginTop': '4px'
    }
    const object = this.props.data
    this.selected = object.selected

    let linkStyle = {
      'display': 'block',
      'color': '#666'
    }
    if (this.selected) {
      liStyle['backgroundColor'] = '#787a8c'
      linkStyle['color'] = '#fff'
    }
    return <li
             data-selected={this.selected}
             style={liStyle}
             onClick={this.onClick.bind(this).bind(object)}
             onMouseEnter={this.onMouseOver.bind(this)}
             onMouseLeave={this.onMouseOut.bind(this)}>
             <a href="#" style={linkStyle}><img alt={object.name} src={object.avatar} style={imgStyle} /> <span style={spanStyle}>{object.name}</span></a>
           </li>
  }
}
