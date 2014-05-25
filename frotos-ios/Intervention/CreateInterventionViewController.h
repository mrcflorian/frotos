//
//  CreateInterventionViewController.h
//  Intervention
//
//  Created by Florian Marcu on 3/22/14.
//  Copyright (c) 2014 Florian Marcu. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "InterventionAPI.h"

@interface CreateInterventionViewController : UIViewController

@property (strong, nonatomic) InterventionAPI *api;
@property (strong, nonatomic) IBOutlet UIBarButtonItem *postButton;
@property (strong, nonatomic) IBOutlet UIButton *addFriendButton;
@property (strong, nonatomic) IBOutlet UITextField *titleInput;
@property (strong, nonatomic) IBOutlet UITextField *friendNameInput;
@property (strong, nonatomic) NSString *userId;
@property (strong, nonatomic) NSArray *friends;

@end
