import React from 'react';
import CSSModules from 'react-css-modules';
import styles from './MainPageProjectTable.css';

class MainPageProjectTable extends React.Component {
    render () {
        const fontStyle = {"fontSize": "11px", "color": "#666666"};
        return (
            <table styleName="spec_table">
                <tbody><tr>
                    <th style={fontStyle}>текущие проекты</th>
                    <th styleName="price_th" style={fontStyle}>цена</th>
                    <th style={fontStyle}>идей</th>
                    <th styleName="term_th" style={fontStyle}>Срок</th>
                </tr>
                <tr styleName="odd">
                    <td styleName="pitches-name">
                        <a href="/pitches/view/108073">Название для оператора сотовой связи </a><br />
                    </td>
                    <td>33 000.-</td>
                    <td>733</td>
                    <td>1 день 23 часа</td>
                </tr>
                <tr styleName="even">
                    <td styleName="pitches-name">
                        <a href="/pitches/view/108288">Логотип для сети кофеен CITY COFFEE HOUSE</a><br />
                    </td>
                    <td>8 000.-</td>
                    <td>91</td>
                    <td>2 дня 10 часов</td>
                </tr>
                <tr styleName="odd">
                    <td styleName="pitches-name">
                        <a href="/pitches/view/108095">Сайт - лэндинг для магазина подгузников и средств по уходу за детьми</a><br />
                    </td>
                    <td>30 000.-</td>
                    <td>10</td>
                    <td>2 дня 17 часов</td>
                </tr>
                </tbody></table>
        );
    }
}

export default CSSModules(MainPageProjectTable, styles);