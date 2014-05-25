//
//  AddFriendsToInterventionViewController.m
//  Intervention
//
//  Created by Florian Marcu on 3/27/14.
//  Copyright (c) 2014 Florian Marcu. All rights reserved.
//

#import "AddFriendsToInterventionViewController.h"

@interface AddFriendsToInterventionViewController ()

@end

@implementation AddFriendsToInterventionViewController

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
    NSNotificationCenter *notificationCenter = [NSNotificationCenter defaultCenter];
    [notificationCenter addObserver:self
                           selector:@selector(searchFriends:)
                               name:UITextFieldTextDidChangeNotification
                             object:_searchTextField];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (IBAction)cancelAddFriendsToIntervention:(id)sender
{
    [self dismissViewControllerAnimated:YES completion:nil];
}

- (IBAction)doneAddFriendsToIntervention:(id)sender
{
}

- (void) searchFriends:(id)notification {
    [_api searchFriendsWithString:_searchTextField.text completionBlock:^(NSDictionary *json) {
        NSMutableArray *friends = [[NSMutableArray alloc] init];
        for (id friend in json) {
            NSDictionary *f = (NSDictionary *) friend;
            [friends addObject:f];
        }
        _friends = friends;
        [_tableView reloadData];
    }];
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    CheckboxFriendCell *cell = [tableView dequeueReusableCellWithIdentifier:@"AddFriendToInterventionStatusCell"];
    
    cell.friendLabel.text = [NSString stringWithFormat:@"%@", [[_friends objectAtIndex: indexPath.row] objectForKey:@"name"]];
    cell.friendImage.image = [UIImage imageWithData:[NSData dataWithContentsOfURL:[NSURL URLWithString:[NSString stringWithFormat:@"%@", [[_friends objectAtIndex: indexPath.row] objectForKey:@"profile_picture"]]]]];
    //cell.friendImage.image = [UIImage imageNamed:@"write.png"];
    return cell;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return [_friends count];
}

@end
