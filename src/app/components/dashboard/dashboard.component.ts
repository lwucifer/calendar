import {Component} from '@angular/core';
import {BaseComponent} from '../../app/base.component';

@Component({
    selector: 'app-dashboard',
    templateUrl: './dashboard.component.html',
    styleUrls: ['./dashboard.component.css']
})

export class DashboardComponent extends BaseComponent {
    results ;
    loaded = false;
    init() {
        this.getData();
    }

    getData() {
        this.get('./api/dashboard').subscribe(res => {
            this.results = this.parseHttpRes(res);
            this.loaded= true;
        });
    }
}
