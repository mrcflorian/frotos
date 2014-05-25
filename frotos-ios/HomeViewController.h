//
//  HomeViewController.h
//  Intervention
//
//  Created by Florian Marcu on 3/20/14.
//  Copyright (c) 2014 Florian Marcu. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "InterventionAccount.h"
#import "InterventionAPI.h"

@interface HomeViewController : UIViewController<UITableViewDelegate, UITableViewDataSource>

@property (nonatomic) InterventionAccount *account;
@property (nonatomic) InterventionAPI *api;

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath;

@end
