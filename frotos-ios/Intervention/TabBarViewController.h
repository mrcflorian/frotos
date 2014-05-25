//
//  TabBarViewController.h
//  Intervention
//
//  Created by Florian Marcu on 3/20/14.
//  Copyright (c) 2014 Florian Marcu. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "InterventionAccount.h"

@interface TabBarViewController : UITabBarController

@property (strong, nonatomic) InterventionAccount *account;

- (void)setAccount:(InterventionAccount *)account;

@end
