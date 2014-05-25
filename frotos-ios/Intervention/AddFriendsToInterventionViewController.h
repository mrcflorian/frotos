//
//  AddFriendsToInterventionViewController.h
//  Intervention
//
//  Created by Florian Marcu on 3/27/14.
//  Copyright (c) 2014 Florian Marcu. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "CheckboxFriendCell.h"
#import "InterventionAPI.h"

@interface AddFriendsToInterventionViewController : UIViewController<UITableViewDelegate, UITableViewDataSource>

@property (strong, nonatomic) IBOutlet UITextField *searchTextField;

@property (strong, nonatomic) IBOutlet UITableView *tableView;

@property (nonatomic) InterventionAPI *api;
@property (nonatomic) NSArray *friends;

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath;

@end
