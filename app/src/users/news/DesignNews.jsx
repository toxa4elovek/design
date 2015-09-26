class DesignNews extends React.Component {
    render() {
        let items = [];
        $.each(this.props, function(i, value) {
            items.push(value);
        });
        return (<div>{ items.map(function(newsItem){
            newsItem.trackLink = 'http://www.godesigner.ru/users/click?link=' + newsItem.link + '&id=' + newsItem.id;
            return <DesignNewsRow key={newsItem.id} newsInfoForRow={newsItem}/>
        }) }</div>)
    }
}