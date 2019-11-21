import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';

import {DashboardComponent} from './components/dashboard/dashboard.component';
import {AdminComponent} from './components/admin/admin.component';
import {AccountComponent} from './components/account/account/account.component';
import {AccountEditComponent} from './components/account/account-edit/account-edit.component';
import {AccountProfileComponent} from './components/account/account-profile/account-profile.component';

import {AccountAddComponent} from './components/account/account-add/account-add.component';
import {CampaignComponent} from './components/campaign/campaign/campaign.component';
import {CampaignEditComponent} from './components/campaign/campaign-edit/campaign-edit.component';
import {CampaignAddComponent} from './components/campaign/campaign-add/campaign-add.component';
import {PlanComponent} from './components/plan/plan/plan.component';
import {PlanEditComponent} from './components/plan/plan-edit/plan-edit.component';
import {PhotoComponent} from './components/photo/photo/photo.component';
import {PhotoEditComponent} from './components/photo/photo-edit/photo-edit.component';
import {StoreComponent} from './components/store/store/store.component';
import {StoreEditComponent} from './components/store/store-edit/store-edit.component';
import {StoreAddComponent} from './components/store/store-add/store-add.component';
import {PhotoAddComponent} from './components/photo/photo-add/photo-add.component';
import {UnlockAccountListComponent} from './components/unlock-account/unlock-account-list.component';
import {ChangePasswordComponent} from './components/change-password/change-password.component';
import {EmailTempalteComponent} from './components/email-tempalte/email-tempalte.component';
import { ManagerCalendarComponent } from './components/calendar/calendar.component';
import { Error404Component } from './error-page/404/404.component';
const appRoutes: Routes = [
    {
        path: 'home',
        component: DashboardComponent,
    },
    {
        path: '',
        component: DashboardComponent,
    },
    {
        path: 'account',
        component: AccountComponent,
    },
    {
        path: 'account/edit/:id',
        component: AccountEditComponent,
    },
    {
        path: 'account/profile',
        component: AccountProfileComponent,
    },
    {
        path: 'account/add',
        component: AccountAddComponent,
    },
    {
        path: 'admin',
        component: AdminComponent,
    },
    {
        path: 'photo',
        component: PhotoComponent,
    },
    {
        path: 'photo/add',
        component: PhotoAddComponent,
    },
    {
        path: 'photo/edit/:id',
        component: PhotoEditComponent,
    },
    {
        path: 'store',
        component: StoreComponent,
    },
    {
        path: 'store/edit/:id',
        component: StoreEditComponent,
    },
    {
        path: 'store/add',
        component: StoreAddComponent,
    },
    {
        path: 'plan',
        component: PlanComponent,
    },
    {
        path: 'plan/edit/:id',
        component: PlanEditComponent,
    },
    {
        path: 'campaign/edit/:id',
        component: CampaignEditComponent,
    },
    {
        path: 'campaign/add',
        component: CampaignAddComponent,
    },
    {
        path: 'campaign',
        component: CampaignComponent,
    },
    {
        path: 'campaign/copy/:id',
        component: CampaignComponent,
    },
    {
        path: 'unlock',
        component: UnlockAccountListComponent,
    },
    {
        path: 'change-password',
        component: ChangePasswordComponent,
    },
    {
        path: 'email-template',
        component: EmailTempalteComponent,
    },
    {
        path: 'store/calendar/:id',
        component: ManagerCalendarComponent,
    },
    { path: '404', component: Error404Component }
];

@NgModule({
    imports: [
        RouterModule.forRoot(appRoutes, {useHash: true})
    ],
    exports: [RouterModule]
})
export class AppRoutingModule {
}
