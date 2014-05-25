//
//  InterventionAPI.h
//  Intervention
//
//  Created by Florian Marcu on 3/20/14.
//  Copyright (c) 2014 Florian Marcu. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "InterventionAccount.h"

@interface InterventionAPI : NSObject

@property (nonatomic) InterventionAccount *account;

- (id)initWithInterventionAccount:(InterventionAccount *)account;
- (void)sendRequestWithURL:(NSString *)url params:(NSString *)params completionBlock:(void (^)(NSDictionary *))completionBlock;
- (void)getMyStatusesWithCompletionBlock:(void (^)(NSDictionary *))completionBlock;
- (void)getFeedWithCompletionBlock:(void (^)(NSDictionary *))completionBlock;
- (void)searchFriendsWithString:(NSString *)query completionBlock:(void (^)(NSDictionary *))completionBlock;
- (void)registerOrUpdateUser;

@end
