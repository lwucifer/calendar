import {Component} from '@angular/core';
import {ViewEncapsulation} from '@angular/core';
import {BaseComponent} from './app/base.component';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.css'],
    encapsulation: ViewEncapsulation.None
})

export class AppComponent extends BaseComponent {
    // private storageKeyRedirect = this.storageKey.CHECK_REDIRECT_LOGIN;

    // ngOnInit() {
    //     const that = this;
    //
    //     setTimeout(function () {
    //         if (this.getItemFromStorage(that.storageKeyRedirect) === false) {
    //             that.detectRedirect();
    //         }
    //     }, 2000);
    // }

    // detectRedirect() {
    //     if (this.isAdmin() || this.isCustomer()) {
    //         return this.redirectNavigate(['/']);
    //     }
    //
    //     if (this.isUser() || this.isManager()) {
    //         return this.redirectNavigate(['store']);
    //     }
    // }
}
