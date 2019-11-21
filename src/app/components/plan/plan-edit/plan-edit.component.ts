import {Component} from '@angular/core';
import {PlanBaseComponent} from '../plan-base.component';

declare var $: any;

@Component({
    selector: 'app-plan-edit',
    templateUrl: './../plan-base.component.html',
    styleUrls: ['./plan-edit.component.css']
})
export class PlanEditComponent extends PlanBaseComponent {
    init() {
        // get current id
        this.id = this.route.snapshot.paramMap.get('id');
        // init form
        this.initform();

        this.get('./api/plan/' + this.id).subscribe(plan => {
            if (!this.checkRequestSuccess(plan)) {
                // redirect 404;
                this.router.navigate(['/404'], {skipLocationChange: true});
                return;
            }
            this.results = this.parseHttpRes(plan);
            if (this.results.status === 4) {
                this.disable_choosen_status = true;
            }

            if (this.results.status === 3) {
                this.disable_choose_time = true;
            }

            this.schedules = this.results['list_data'].length !== 0 ? this.results['list_data'] : [{
                time_start: this.results['start_time'],
                time_end: this.results['end_time'],
                status: 2,
                id: 0
            }];
            this.assignform(this.results);
            this.get('./api/store/' + this.results.store.id).subscribe(store => {
                this.stores = this.parseHttpRes(store);
            });
            this.http.get('./api/campaign/' + this.results.campaign.id).subscribe(campaign => {
                this.campaigns = this.parseHttpRes(campaign);
            });
            this.all_option = JSON.parse(this.results['option'][0]['content']);
            this.custom_info = JSON.parse(this.results['data_customer']);
            this.loaded = true;
        });

    }

    onSubmit(data) {
        this.preSubmit(data);
        if (!this.showMessageRequire) {
            this.patch('./api/plan/' + this.id, data).subscribe(res => {
                this.handlesubmit(res);
            });
        }
    }
}
