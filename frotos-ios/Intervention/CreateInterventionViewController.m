//
//  CreateInterventionViewController.m
//  Intervention
//
//  Created by Florian Marcu on 3/22/14.
//  Copyright (c) 2014 Florian Marcu. All rights reserved.
//

#import "CreateInterventionViewController.h"
#import "AddFriendsToInterventionViewController.h"

@interface CreateInterventionViewController ()

@end

@implementation CreateInterventionViewController

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
	// Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (IBAction)createIntervention:(id)sender
{
    
}

- (IBAction)cancelCreateIntervention:(id)sender
{
    [self dismissViewControllerAnimated:YES completion:nil];
}

- (IBAction)addFriends:(id)sender
{
    UINavigationController *controller = [[self storyboard] instantiateViewControllerWithIdentifier:@"AddFriendsToInterventionNav"];
    
    AddFriendsToInterventionViewController *viewController = (AddFriendsToInterventionViewController*) controller.viewControllers.firstObject;
    viewController.api = _api;
    [_api searchFriendsWithString:@"" completionBlock:^(NSDictionary *json) {
        //NSLog(@"%@", json);
        NSMutableArray *friends = [[NSMutableArray alloc] init];
        for (id friend in json) {
            NSDictionary *f = (NSDictionary *) friend;
            [friends addObject:f];
        }
        viewController.friends = friends;
        [self presentViewController:controller animated:YES completion:nil];
        
    }];
}

@end
