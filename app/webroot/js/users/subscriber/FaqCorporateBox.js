var FaqCorporateBox = React.createClass({displayName: "FaqCorporateBox",
    render: function() {
        return (React.createElement("ul", null, React.createElement("li", {className: "faq-corporate-title"}, "FAQ"), 
             this.props.data.map(function(object, index){
                return React.createElement(FaqQuestionRow, {object: object})})
        ))},
});
var questions = [
    {title: 'Как наша компания может заключить с вами договор?', id: '82'},
    {title: 'Какие способы оплаты мы принимаем?', id: '6'},
    {title: 'Если я создаю проект от лица компании?', id: '89'}
];
React.render(
React.createElement(FaqCorporateBox, {data: questions}),
    $('#faq-corporate')[0]
);