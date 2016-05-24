import React from 'react';
import CSSModules from 'react-css-modules';
import styles from './PushWidget.css';
import PushWidgetActions from './PushWidgetActions.jsx'

export default class PushWidget extends React.Component{
    constructor(props) {
        super(props);
        this.confirmButtonClick = this.confirmButtonClick.bind(this);
        this.denyButtonClick = this.denyButtonClick.bind(this);
        this.fluxActions = new PushWidgetActions;
        this.state = {
            "display": "block"
        };
    }
    confirmButtonClick() {
        this.fluxActions.confirmClick();
        this.setState({
            "display": "none"
        });
        return false;
    }
    denyButtonClick() {
        this.fluxActions.denyClick();
        this.setState({
            "display": "none"
        });
        return false;
    }
    render() {
        return (
            <div style={this.state} className={styles['push-widget-box']}>
                <header>
                    <h3>Показывать важные<br/>
                    новости в браузере?</h3>
                </header>
                <main>
                    <span onClick={this.confirmButtonClick} className={styles['push-widget-box-confirm-button']}>Да, хочу знать!</span>
                    <span onClick={this.denyButtonClick} className={styles['push-widget-box-deny-button']}>не нужно</span>
                </main>
            </div>
        );
    }
}