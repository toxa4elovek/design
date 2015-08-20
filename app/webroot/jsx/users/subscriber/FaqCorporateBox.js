var FaqCorporateBox = React.createClass({
    render: function() {
        return (<ul><li className="faq-corporate-title">FAQ</li>
            { this.props.data.map(function(object, index){
                return <FaqQuestionRow object={object} />})}
        </ul>)},
});
var questions = [
    {title: 'Как наша компания может заключить с вами договор?', id: '82'},
    {title: 'Какие способы оплаты мы принимаем?', id: '6'},
    {title: 'Если я создаю проект от лица компании?', id: '89'}
];
React.render(
<FaqCorporateBox data={questions} />,
    $('#faq-corporate')[0]
);