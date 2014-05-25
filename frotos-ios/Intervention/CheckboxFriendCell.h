//
//  CheckboxFriendCell.h
//  Intervention
//
//  Created by Florian Marcu on 3/27/14.
//  Copyright (c) 2014 Florian Marcu. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface CheckboxFriendCell : UITableViewCell

@property (strong, nonatomic) IBOutlet UIButton *checkBox;
@property (strong, nonatomic) IBOutlet UILabel *friendLabel;
@property (strong, nonatomic) IBOutlet UIImageView *friendImage;

@end
