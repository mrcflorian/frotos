//
//  InterventionAccount.h
//  Intervention
//
//  Created by Florian Marcu on 3/20/14.
//  Copyright (c) 2014 Florian Marcu. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <FacebookSDK/FacebookSDK.h>

@interface InterventionAccount : NSObject

@property (nonatomic) NSString *userId;
@property (nonatomic) NSString *fbId;
@property (nonatomic) NSString *firstName;
@property (nonatomic) NSString *lastName;
@property (nonatomic) NSString *email;
@property (nonatomic) NSString *sex;
@property (nonatomic) NSString *location;
@property (nonatomic) NSString *birthday;
@property (nonatomic) NSString *profilePicture;
@property (nonatomic) NSArray *friends;

- (id)initWithFacebookUserInfo:(id)user_info;
- (void)setFriendsWithFbResult:(id)result;

@end
