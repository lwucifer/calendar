import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {APP_BASE_HREF} from '@angular/common';
import {AppRoutingModule} from './app-routing.module';
import {HttpClientModule, HttpClient} from '@angular/common/http';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';

import {AppComponent} from './app.component';
import {MenuComponent} from './components/menu/menu.component';
import {DashboardComponent} from './components/dashboard/dashboard.component';
import {AdminComponent} from './components/admin/admin.component';
import {OrderModule} from './order-pipe/ngx-order.module';
import {AccountComponent} from './components/account/account/account.component';
import {AccountEditComponent} from './components/account/account-edit/account-edit.component';

import {CampaignComponent} from './components/campaign/campaign/campaign.component';
import {CampaignEditComponent} from './components/campaign/campaign-edit/campaign-edit.component';
import {PlanComponent} from './components/plan/plan/plan.component';
import {PlanEditComponent} from './components/plan/plan-edit/plan-edit.component';
import {PhotoComponent} from './components/photo/photo/photo.component';
import {PhotoEditComponent} from './components/photo/photo-edit/photo-edit.component';
import {StoreComponent} from './components/store/store/store.component';
import {StoreEditComponent} from './components/store/store-edit/store-edit.component';
import {AccountAddComponent} from './components/account/account-add/account-add.component';
import {PaginationComponent} from './components/pagination/pagination.component';

import {ConfirmationDialogComponent} from './services/confirmation-dialog/confirmation-dialog.component';
import {ConfirmationDialogService} from './services/confirmation-dialog/confirmation-dialog.service';
import {NgbModule} from '@ng-bootstrap/ng-bootstrap';
import {PhotoAddComponent} from './components/photo/photo-add/photo-add.component';
import {StoreAddComponent} from './components/store/store-add/store-add.component';
import {CampaignAddComponent} from './components/campaign/campaign-add/campaign-add.component';
import {ImportComponent} from './components/import/import.component';
import {JsonhelperService} from './services/jsonhelper/jsonhelper.service';
import {OwlDateTimeModule, OwlNativeDateTimeModule} from 'ng-pick-datetime';
import {UnlockAccountListComponent} from './components/unlock-account/unlock-account-list.component';
import {TranslateModule, TranslateLoader} from '@ngx-translate/core';
import {TranslateHttpLoader} from '@ngx-translate/http-loader';
import { Error404Component } from './error-page/404/404.component';
export function HttpLoaderFactory(http: HttpClient) {
    return new TranslateHttpLoader(http, './i18n/', '.json');
}

import {AccountProfileComponent} from './components/account/account-profile/account-profile.component';
import {ChangePasswordComponent} from './components/change-password/change-password.component';
import {DragulaModule} from 'ng2-dragula';
import {EmailTempalteComponent} from './components/email-tempalte/email-tempalte.component';
import {ManagerCalendarComponent} from './components/calendar/calendar.component';
import {FullCalendarModule} from 'ng-fullcalendar';


@NgModule({
    declarations: [
        AppComponent,
        MenuComponent,
        DashboardComponent,
        AdminComponent,
        AccountAddComponent,
        AccountComponent,
        AccountEditComponent,
        AccountProfileComponent,
        CampaignComponent,
        CampaignEditComponent,
        PlanComponent,
        PlanEditComponent,
        PhotoComponent,
        PhotoEditComponent,
        StoreComponent,
        StoreEditComponent,
        PaginationComponent,
        ConfirmationDialogComponent,
        PhotoAddComponent,
        StoreAddComponent,
        CampaignAddComponent,
        ImportComponent,
        UnlockAccountListComponent,
        ChangePasswordComponent,
        EmailTempalteComponent,
        ManagerCalendarComponent,
        Error404Component
    ],
    imports: [
        BrowserModule,
        BrowserAnimationsModule,
        OwlDateTimeModule,
        OwlNativeDateTimeModule,
        AppRoutingModule,
        OrderModule,
        HttpClientModule,
        FormsModule,
        FullCalendarModule,
        ReactiveFormsModule,
        NgbModule.forRoot(),
        DragulaModule.forRoot(),
        TranslateModule.forRoot({
            loader: {
                provide: TranslateLoader,
                useFactory: HttpLoaderFactory,
                deps: [HttpClient]
            }
        })
    ],
    providers: [
        {provide: APP_BASE_HREF, useValue: '/'},
        ConfirmationDialogService,
        JsonhelperService
    ],
    entryComponents: [ConfirmationDialogComponent],
    bootstrap: [AppComponent]
})
export class AppModule {
}
