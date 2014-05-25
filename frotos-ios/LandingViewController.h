//
//  LandingViewController.h
//  Intervention
//
//  Created by Florian Marcu on 3/20/14.
//  Copyright (c) 2014 Florian Marcu. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <FacebookSDK/FacebookSDK.h>
#import "InterventionAccount.h"

@interface LandingViewController : UIViewController

@property (strong, nonatomic) IBOutlet UIButton *loginButton;
@property (strong, nonatomic) IBOutlet UILabel *logoLabel;

@property (strong, nonatomic) InterventionAccount *account;

@end
